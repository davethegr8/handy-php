<?php

namespace Hep\Foundation;

class Flash {
    protected $message = '<p class="message">{message}</p>';
    protected $group = '<div class="alert alert-{type}">{messages}</div>';
    protected $container = '<div class="messages">{html}</div>';

    protected $messages = [];

    public function __construct() {
        if(isset($_SESSION['flash']) && count($_SESSION['flash'])) {
            $this->messages = $_SESSION['flash'];
        }
    }

    public function __destruct() {
        if(count($this->messages)) {
            $_SESSION['flash'] = $this->messages;
        }
    }

    public function set($message, $type = 'info') {
        if(!is_array($this->messages)) {
            $this->messages = [];
        }
        $this->messages[$type][] = $message;
    }

    public function success($message) {
        $this->set($message, 'success');
    }

    public function error($message) {
        $this->set($message, 'error');
    }

    public function info($message) {
        $this->set($message, 'info');
    }

    public function warning($message) {
        $this->set($message, 'warning');
    }

    public function get($type = '') {
        if(!isset($this->messages) || !is_array($this->messages)) {
            return [];
        }

        if($type) {
            if(!is_array($this->messages[$type])) {
                return [];
            }

            return $this->messages[$type];
        }

        return $this->messages;
    }

    public function display($type = '') {
        $html = '';
        if(!isset($this->messages) || !count($this->messages)) {
            return $html;
        }

        foreach($this->messages as $type => $messages) {
            $context = [
                'type' => $type,
                'messages' => []
            ];

            foreach($messages as $message) {
                $context['messages'][] = $this->render($this->message, [
                    'message' => $message
                ]);
            }

            $context['messages'] = implode('\n', $context['messages']);
            $html .= $this->render($this->group, $context);

            unset($this->messages[$type]);
        }

        return $this->render($this->container, ['html' => $html]);
    }

    public function count($type = '') {
        if($type) {
            return count($this->messages[$type]);
        }
        else {
            return count($this->messages, COUNT_RECURSIVE);
        }
    }

    public function clear() {
        $this->messages = [];
    }

    public function render($message, $data) {
        foreach($data as $key => $value) {
            $message = str_replace('{' . $key . '}', $data[$key], $message);
        }
        return $message;
    }
}
