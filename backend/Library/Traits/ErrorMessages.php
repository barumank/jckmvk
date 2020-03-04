<?php
/**
 * @author: Yankovskiy Danill <qdanik@yandex.ru>
 *
 * Copyright (c) 09.09.17 13:29
 */

namespace Backend\Library\Traits;

trait ErrorMessages {

    public function getErrorMessages() {
        $output = [];

        /** @var \Phalcon\Forms\Form $this */
        foreach ( $this->getMessages() as $message ) {
            $output[$message->getField()] = $message->getMessage();
        }

        return $output;
    }
}