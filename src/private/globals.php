<?php

// globals
const LOCK_SHARE = 0;
const LOCK_UPDATE = 1;
const NO_LOCK = -1;

// the data field types
const CHAR_TYPE = 1;
const NUMBER_TYPE = 2;
const DATE_TYPE = 3;
const DATE_TIME_TYPE = 4;
const CLOB_TYPE = 5;

// the standard data fields
const ID_FIELD = 'id';
const UPDATE_COUNTER_FIELD = 'uc';
const NEW_UPDATE_COUNTER_FIELD = 'new_uc';

const EMAIL_PATTERN = '^[a-zA-Z0-9_.-]+@[a-zA-Z]+[a-zA-Z.-]+\.[a-zA-Z-]+[a-zA-Z]+$';

function is_valid_email($email)
{
    return preg_match(EMAIL_PATTERN, $email);
}

function maestro_info(): string
{
    return 'maestro v2010.2';
}

function is_empty($value)
{
    if (is_string($value)) {
        return is_null($value) || trim($value) === '';
    }
    return is_null($value);
}

