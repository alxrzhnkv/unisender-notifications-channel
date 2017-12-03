<?php

namespace NotificationChannels\Unisender;

class UnisenderMessage
{
    public $to;
    public $from;
    public $content;
    public $token;
    public $silent = false;

    /**
     * Set API Token.
     *
     * @param string $token
     *
     * @return UnisenderMessage
     */
    public function usingApiToken(string $token)
    {
        $this->token = $token;

        return $this;
    }

    /**
     * Send message silently (without raising any exceptions).
     *
     * @param bool $flag
     *
     * @return UnisenderMessage
     */
    public function silent(bool $flag = true)
    {
        $this->silent = $flag;

        return $this;
    }

    /**
     * Set the message's receivers.
     *
     * @param array|string $to
     *
     * @return UnisenderMessage
     */
    public function to($to)
    {
        if (is_array($to)) {
            $to = implode(',', $to);
        }

        $this->to = $to;

        return $this;
    }

    /**
     * Set the message's sender.
     *
     * @param string $from
     *
     * @return UnisenderMessage
     */
    public function from(string $from)
    {
        $this->from = $from;

        return $this;
    }

    /**
     * Set the message's content.
     *
     * @param string $content
     *
     * @return UnisenderMessage
     */
    public function content(string $content)
    {
        $this->content = $content;

        return $this;
    }
}
