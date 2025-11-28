<?php

namespace ProNetwork\Support\Enums;

enum SecurityEventType: string
{
    case BRUTE_FORCE = 'brute_force';
    case SUSPICIOUS_IP = 'suspicious_ip';
    case DB_THREAT = 'db_threat';
    case BLOCKED_ACTION = 'blocked_action';
    case RATE_LIMIT = 'rate_limit';
}
