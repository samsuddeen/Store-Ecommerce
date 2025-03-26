<?php

    namespace App\Actions\Email;

    class DefaultEmail{

        protected $status=null;
        protected $title=null;
        protected $message=null;
        public function __construct($status=null,$title=null,$message=null)
        {
            $this->status=$status;
            $this->title=$title;
            $this->message=$message;
            $this->defaultEmail($status);
        }
        public function defaultEmail($status=null)
        {
            switch($status)
            {
                case 1:
                    $this->getMessage();
                break;
            }
            
        }

        public function getMessage()
        {
            $html="Dear";
            return $html;
        }

    }