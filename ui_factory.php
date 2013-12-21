<?php

global $config;

$config = [
    'handlers' => [
        'segment' => 'ui_widget_element_segment'
    ]
];

interface renderable_widget {
    public function render();
}

trait ui_properties_mixin {
    
    public $_properties = [];
    
    public function prop($k, $v) {
        $this->_properties[$k] = $v;
    }
    
    public function mixin() {
        $out = '';
        foreach ($this->_properties as $k => $v) {
            $out .= '"' .$k . '"="' . $v . '"';
        }
        return $out;
    }
}

trait ui_classes_mixin {
    
    public $_classes = [];
    
    public function add_class($class) {
        
        if (is_string($class)) {
            $this->_classes[] = $class;
        } else if (is_array($class)) {
            $this->_classes = array_merge($this->_classes, $class);
        }
        
        return $this;
    }
    
    public function mixin() {
        return implode(' ', $this->_classes);
    }
}

trait ui_content_mixin {
    public $_content = [];
    
    public function content($content) {
        $this->_content[] = [$content];
    }
    
    public function mixin() {
        $out = '';
        
        foreach ($this->_content as $content) {
            $c = array_pop($content);
            
            if ($c instanceof renderable_widget) {
                $out .= $c->render();
            } else {
                $out .= $c;
            }
        }
        
        return $out;
    }
}

/// semantic ui additions
trait ui_icon_mixin {
    
    public $_icon = '';
    
    public function icon() {
        $args = func_get_args();
        $icon = array_shift($args);
        
        $this->_icon = $icon . implode(' ', $args) . ' icon';
        
        return $this;
    }
    
    public function mixin() {
        return '<i class="' . $this->_icon . '"></i>';
    }
}

trait ui_size_mixin {
    use ui_classes_mixin;
    
    public $_size = '';
    
    private function resolve($size) {
        if ($this->_size === '') {
            $this->_size = $size;
            $this->add_class($size);
        } else if ($this->_size != $size) {
            $this->remove_class($this->_size)->add_class($size);
            $this->_size = $size;
        }
    }
    
    public function mini() {
        return $this->resolve(method_get_name());
    }
    
    public function tiny() {
        return $this->resolve(method_get_name());
    }
    
    public function small() {
        return $this->resolve(method_get_name());
    }
    
    public function medium() {
        return $this->resolve(method_get_name());
    }
    
    public function large() {
        return $this->resolve(method_get_name());
    }
    
    public function big() {
        return $this->resolve(method_get_name());
    }
    
    public function huge() {
        return $this->resolve(method_get_name());
    }
    
    public function massive() {
        return $this->resolve(method_get_name());
    }
}


trait ui_tag_mixin {
    
    public $tag = 'div';
    
    public $short_tags = ['br', 'input'];
    
    public function start($props = '') {
        
        if (in_array($this->tag, $this->short_tags)) {
            return '<' . $this->tag . $props . '/>';
        } else {
            return '<' . $this->tag . $props . '>';
        }
    }
    
    public function end() {
        return '</' . $this->tag . '>';
    }
}

class ui_html implements renderable_widget {
    use ui_tag_mixin, ui_content_mixin;
    
    public function __construct($tag, $content) {
        $this->tag = $tag;
        $this->content($content);
    }
    
    public function render() {
        $out = $this->start();
        $out .= ui_content_mixin::mixin();
        $out .= $this->end();
        
        return $out;
    }
}

trait ui_widget_traits {
    
    public $classes = [];
    public $properties = [];
    public $inline = '';
    public $tag = 'div';
    
    public $content;
    
    public function print_classes() {
        return implode(' ', $this->classes);
    }

    public function print_properties() {
        $out = '';
        foreach ($this->properties as $k => $v) {
            $out .= '"' .$k . '"="' . $v . '"';
        }
        return $out;
    }
    
    public function print_inline() {
        return $this->inline;
    }
    
    public function inline($str) {
        $this->inline .= $str;
    }
    
    public function print_start() {
        $start = '<' . $this->tag . ' ';
        $start .= 'class="' . $this->print_classes() . '" ';
        $start .= $this->print_properties() . ' ';
        $start .= '>';
        
        echo $start;
    }
    
    public function print_content() {
        echo $this->content;
    }
    
    public function print_end() {
        $end = '</' . $this->tag . '>';
        echo $end;
    }
}

class ui_widget {
//    use ui_widget_traits;
    use ui_properties_mixin, ui_icon_mixin, ui_classes_mixin;
    
    public function __construct($args) {
        
        $count = count($args);
        
        if ($count== 1) {
            $this->content = array_pop($args[0]);
//            var_dump($this->content);
        }
    }

    public function __call($name, $arguments) {
        
        if ($name === 'render') {
            throw new Exception('ouch!');
        }
        
        if (empty($arguments)) {
            $this->classes[] = $name;
        } else {
            $this->properties[$name] = $arguments;
        }
        
        return $this;
    }
    
    public function render() {
        $properties = ui_properties_mixin::mixin();
        $classes = ui_classes_mixin::mixin();
        
        $out = $this->start($properties . $classes);
        $out .= $this->end();
        
        return $end;
    }
}


class ui_widget_element_segment extends ui_widget implements renderable_widget {
    
    public function __construct($args) {
        $this->classes = ['ui', 'segment'];
        parent::__construct($args);
    }

    public function render() {
        parent::render();
    }
}

class ui_handler {
    
    private function __construct($config) {
        
    }
    
    public static function __callStatic($name, $arguments) {
        global $config;
        
        $handler  = $config['handlers'];
        
        $t= new $handler[$name]($arguments);
        return $t;
        
//        forward_static_call($name, $arguments);
//        return new ui_widget($name, $arguments);
    }

}


class ui {
    
    public static function __callStatic($name, $arguments) {
        try {
            return ui_handler::$name($arguments);
            
        } catch (UIException $err) {
            $err->render();
        }
    }
}
