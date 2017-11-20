<?php

namespace app\util;

class DateTimeHelper
{

    const HOURS = 'hours';
    const MINUTES = 'minutes';
    const SECONDS = 'seconds';

    const DATE_CODE_YEAR = 1;
    const DATE_CODE_MONTH = 2;
    const DATE_CODE_WEEK = 3;
    const DATE_CODE_DAY = 4;
    const DATE_CODE_HOUR = 5;
    const DATE_CODE_MINUTE = 6;

    const FMT_DMY_HIS = 'd-m-Y H:i:s';
    const FMT_YMD_HIS = 'Y-m-d H:i:s';
    const FMT_DMY_HI = 'd-m-Y H:i';
    const FMT_YMD = 'Y-m-d';
    const FMT_HIS = 'H:i:s';

    const WORKING_TIME_OFFSET_CONSTANT = 4;
    const SECONDS_IN_DAY = 86400;
    const NUMBER_OF_DAYS_IN_WEEK = 7;
    const MONTHS_IN_QUARTER = 3;
    const HOURS_IN_WEEK = 168;
    const SECONDS_IN_HOUR = 3600;

    /**
     * @return array
     */
    public static function getDateCodeMap()
    {
        return [
            [
                'letter' => 'h',
                'code'   => static::DATE_CODE_HOUR,
            ],
            [
                'letter' => 'd',
                'code'   => static::DATE_CODE_DAY,
            ],
            [
                'letter' => 'w',
                'code'   => static::DATE_CODE_WEEK,
            ],

            [
                'letter' => 'm',
                'code'   => static::DATE_CODE_MONTH,
            ],
            [
                'letter' => 'y',
                'code'   => static::DATE_CODE_YEAR,
            ],
        ];
    }

    /**
     * @param $time
     * @param bool $timezone
     *
     * @return int
     */
    public static function endOfDayTimestamp($time, $timezone = false)
    {
        return static::startOfDayTimestamp($time, $timezone) + 86400; // timestamp - end of current day
    }

    /**
     * @param $time
     * @param bool $timezone
     *
     * @return int
     */
    public static function startOfYesterdayTimestamp($time, $timezone = false)
    {
        return static::startOfDayTimestamp($time, $timezone) - 86400; // timestamp - end of current day
    }

    /**
     * @param $time
     * @param bool $timezone
     *
     * @return int
     */
    public static function startOfDayTimestamp($time, $timezone = false)
    {
        $dtNow = new \DateTime();
        // Set a non-default timezone if needed
        if ($timezone) {
            $dtNow->setTimezone(new \DateTimeZone($timezone));
        }
        $dtNow->setTimestamp($time);
        $dtNow->setTime(0, 0, 0);

        // $dtNow->modify('tomorrow');

        return $dtNow->getTimestamp();

        // return strtotime( date('Y-m-d', $time) . ' 00:00:00'); // timestamp - end of current day
    }

    /**
     * @param $time
     * @param bool $timezone
     *
     * @return int
     */
    public static function endOfWeekTimestamp($time, $timezone = false)
    {
        $dtNow = new \DateTime();
        // Set a non-default timezone if needed
        if ($timezone) {
            $dtNow->setTimezone(new \DateTimeZone($timezone));
        }
        $dtNow->setTimestamp($time);
        $dtNow->setTime(0, 0, 0);
        $dtNow->modify('sunday this week +1 day');

        $endOfWeekTimestamp = $dtNow->getTimestamp();

        return $endOfWeekTimestamp > $time + 3600 * 24 * 7
            ? $endOfWeekTimestamp - 3600 * 24 * 7
            : $endOfWeekTimestamp;

        // return strtotime('sunday this week +1 day', $time); // timestamp - end of current day
    }

    /**
     * @param $time
     * @param bool $timezone
     *
     * @return int
     */
    public static function startOfWeekTimestamp($time, $timezone = false)
    {
        $dtNow = new \DateTime();
        // Set a non-default timezone if needed
        if ($timezone) {
            $dtNow->setTimezone(new \DateTimeZone($timezone));
        }
        $dtNow->setTimestamp($time);
        $dtNow->setTime(0, 0, 0);
        $dtNow->modify('sunday last week +1 day');

        $startOfWeekTimestamp = $dtNow->getTimestamp();

        return $startOfWeekTimestamp > $time
            ? $startOfWeekTimestamp - 3600 * 24 * 7
            : $startOfWeekTimestamp;

        // return strtotime('sunday last week +1 day', $time); // timestamp - end of current day
    }

    public static function startOfLastWeekTimestamp($time, $timezone = false)
    {
        $dtNow = new \DateTime();
        // Set a non-default timezone if needed
        if ($timezone) {
            $dtNow->setTimezone(new \DateTimeZone($timezone));
        }
        $dtNow->setTimestamp($time);
        $dtNow->setTime(0, 0, 0);
        $dtNow->modify('sunday last week +1 day -1 week');

        return $dtNow->getTimestamp();


        //return strtotime('sunday last week +1 day -1 week', $time); // timestamp - end of current day
    }

    /**
     * @param $time
     * @param bool $timezone
     *
     * @return int
     */
    public static function startOfLastMonthTimestamp($time, $timezone = false)
    {
        $dtNow = new \DateTime();
        // Set a non-default timezone if needed
        if ($timezone) {
            $dtNow->setTimezone(new \DateTimeZone($timezone));
        }
        $dtNow->setTimestamp($time);
        $tst = $dtNow->getTimestamp();

        $dtNow->setDate(date('Y', $tst), date('m', $tst), 1);
        $dtNow->setTime(0, 0, 0);
        $dtNow->modify('this month -1 month');

        return $dtNow->getTimestamp();

        // return strtotime(date('01.m.Y H:i:s', strtotime('this month -1 month', $time)));
    }

    public static function startOfThisMonthTimestamp($time, $timezone = false)
    {
        $dtNow = new \DateTime();
        // Set a non-default timezone if needed
        if ($timezone) {
            $dtNow->setTimezone(new \DateTimeZone($timezone));
        }
        $dtNow->setTimestamp($time);
        $tst = $dtNow->getTimestamp();

        $dtNow->setDate(date('Y', $tst), date('m', $tst), 1);
        $dtNow->setTime(0, 0, 0);
        $dtNow->modify('this month');

        return $dtNow->getTimestamp();

        // return strtotime(date('01.m.Y H:i:s', $time));
    }

    /**
     * @param $time
     * @param bool $timezone
     *
     * @return int
     */
    public static function endOfMonthTimestamp($time, $timezone = false)
    {
        $dtNow = new \DateTime();
        // Set a non-default timezone if needed
        if ($timezone) {
            $dtNow->setTimezone(new \DateTimeZone($timezone));
        }
        $dtNow->setTimestamp($time);
        $dtNow->setTime(0, 0, 0);
        $dtNow->modify('last day of this month');

        return $dtNow->getTimestamp() + 86400;

        //return strtotime('last day this month +1 month 00:00:00', $time); // timestamp - end of current month
    }

    /**
     * @param $time
     * @param bool $timezone
     *
     * @return int
     */
    public static function endOfLastMonthTimestamp($time, $timezone = false)
    {
        $dtNow = new \DateTime();
        // Set a non-default timezone if needed
        if ($timezone) {
            $dtNow->setTimezone(new \DateTimeZone($timezone));
        }
        $dtNow->setTimestamp($time);
        $dtNow->setTime(0, 0, 0);
        $dtNow->modify('last day of previous month');

        return $dtNow->getTimestamp();

    }

    public static function startOfThisYearTimestamp($time, $timezone = false)
    {
        $dtNow = new \DateTime();
        // Set a non-default timezone if needed
        if ($timezone) {
            $dtNow->setTimezone(new \DateTimeZone($timezone));
        }
        $dtNow->setTimestamp($time);
        $tst = $dtNow->getTimestamp();

        $dtNow->setDate(date('Y', $tst), 1, 1);
        $dtNow->setTime(0, 0, 0);

        return $dtNow->getTimestamp();
    }

    /**
     * @param $time
     * @param bool $timezone
     *
     * @return int
     */
    public static function endOfYearTimestamp($time, $timezone = false)
    {
        $dtNow = new \DateTime();
        // Set a non-default timezone if needed
        if ($timezone) {
            $dtNow->setTimezone(new \DateTimeZone($timezone));
        }
        $dtNow->setTimestamp($time);
        $tst = $dtNow->getTimestamp();

        $dtNow->setDate(date('Y', $tst), 12, 31);
        $dtNow->setTime(23, 59, 59);

        return $dtNow->getTimestamp();
    }

    /**
     * @param $time
     * @param bool $timezone
     *
     * @return int
     */
    public static function startOfLastYearTimestamp($time, $timezone = false)
    {
        $dtNow = new \DateTime();
        // Set a non-default timezone if needed
        if ($timezone) {
            $dtNow->setTimezone(new \DateTimeZone($timezone));
        }
        $dtNow->setTimestamp($time);
        $tst = $dtNow->getTimestamp();

        $dtNow->setDate(date('Y', $tst), 1, 1);
        $dtNow->setTime(0, 0, 0);
        $dtNow->modify('-1 year');

        return $dtNow->getTimestamp();

        // return strtotime(date('01.m.Y H:i:s', strtotime('this month -1 month', $time)));
    }

    /**
     * @param $time
     * @param bool $timezone
     *
     * @return int
     */
    public static function endOfLastYearTimestamp($time, $timezone = false)
    {
        $dtNow = new \DateTime();
        // Set a non-default timezone if needed
        if ($timezone) {
            $dtNow->setTimezone(new \DateTimeZone($timezone));
        }
        $dtNow->setTimestamp($time);
        $tst = $dtNow->getTimestamp();

        $dtNow->setDate(date('Y', $tst), 12, 31);
        $dtNow->setTime(23, 59, 59);
        $dtNow->modify('-1 year');

        return $dtNow->getTimestamp();
    }

    /**
     * @param $tz1
     * @param $tz2
     * @return float
     */
    public static function getHoursDiff($tz1, $tz2)
    {
        if (!$tz1) {
            $tz1 = \date_default_timezone_get();
        }
        $diff = static::getDiff($tz1, $tz2);

        return $diff / 3600;
    }

    /**
     * @param $tz1
     * @param $tz2
     *
     * @return int
     */
    public static function getDiff($tz1, $tz2)
    {
        if (!$tz1) {
            $tz1 = \date_default_timezone_get();
        }
        if (!$tz2) {
            return 0;
        }
        $timezone = new \DateTimeZone($tz1);
        $date1 = new \DateTime('now', $timezone);
        $date2 = clone $date1;
        $date1 = (array)$date1;
        $date1 = $date1['date'];
        $ts1 = strtotime($date1);

        $timezone = new \DateTimeZone($tz2);
        $date2->setTimezone($timezone);
        $date2 = (array)$date2;
        $date2 = $date2['date'];
        $ts2 = strtotime($date2);

        return ($ts1 - $ts2);
    }

    /**
     * @param $tz
     *
     * @return float
     */
    public static function getServerHoursDiff($tz)
    {
        return static::getHoursDiff($tz, \date_default_timezone_get());
    }

    /**
     * @param $tz
     *
     * @return int
     */
    public static function getServerDiff($tz)
    {
        return static::getDiff($tz, \date_default_timezone_get());
    }

    /**
     * @param $ts
     * @param $tz
     *
     * @return int
     */
    public static function applyTimeZone($ts, $tz)
    {
        return $ts + static::getServerDiff($tz);
    }

    /**
     * @param int $ts
     * @param string $tz
     *
     * @return int
     */
    public static function getServerTimeByTzTime($ts, $tz)
    {
        return $ts - static::getServerDiff($tz);
    }

    public static function applyServerTimeZone($ts)
    {
        return static::applyTimeZone($ts, \date_default_timezone_get());
    }

    public static function now($diffDays = false, $useSeconds = true)
    {
        if (!$diffDays) {
            return date(
                'Y-m-d' . ($useSeconds
                    ? ' H:i:s'
                    : ' 00:00:00')
            );
        }

        return date(
            'Y-m-d' . ($useSeconds
                ? ' H:i:s'
                : ' 00:00:00'), strtotime(
                $diffDays < 0
                    ? $diffDays . ' DAYS'
                    : '+' . $diffDays . ' DAYS'
            )
        );
    }

    public static function timezoneNow($format = \DateTime::W3C)
    {
        return (new \DateTime())->format($format);
    }

    /**
     * @return int
     */
    public static function nowStamp()
    {
        return time();
    }

    /**
     * @return array
     */
    public static function getTimeZones()
    {
        $list = \DateTimeZone::listIdentifiers();

        return array_combine($list, $list);
    }

    /**
     * @return array
     */
    public static function getTimeZonesAndDiff()
    {
        $list = static::getTimeZonesAndDiffArray();
        $res = [];
        foreach ($list as $timezone => $diff) {
            $res[$timezone] = $timezone . ' (GMT' . ($diff != '+0:00'
                    ? ' ' . $diff
                    : '') . ')';
        }

        return $res;
    }

    /**
     * @param $tz
     *
     * @return bool
     */
    public static function getTimeZoneOffset($tz)
    {
        $list = static::getTimeZonesAndDiffArray();
        if (isset($list[$tz])) {
            $value = str_replace('+', '', $list[$tz]);
            list($hours, $minutes) = explode(':', $value);

            return $hours * 60 + $minutes;
        }

        return false;
    }

    public static function getTimeZoneByOffset($tzOffset)
    {
        $list = static::getTimeZonesAndDiffArray();
        if ($list) {
            foreach ($list as $tz => $diff) { // Europe/Moscow => +4:00
                $value = str_replace('+', '', $diff);
                list($hours, $minutes) = explode(':', $value);
                if (($hours * 60 + $minutes) == $tzOffset) {
                    return $tz;
                }
            }
        }

        return false;
    }

    /**
     * @param $timestamp
     *
     * @return string
     */
    public static function convertToHours($timestamp)
    {
        $sign = false;
        if ($timestamp < 0) {
            $sign = true;
            $timestamp = abs($timestamp);
        }
        settype($timestamp, 'integer');
        $hours = floor($timestamp / 60);
        $minutes = ($timestamp % 60);

        return ($sign
                ? '-'
                : '+') . $hours . ':' . ($minutes < 10
                ? '0' . $minutes
                : $minutes);
    }

    /**
     * @return array
     */
    public static function getTimeZonesAndDiffArray()
    {
        $list = \DateTimeZone::listIdentifiers();
        $res = [];
        foreach ($list as $timezone) {
            $diff = static::getHoursDiff($timezone, 'UTC');
            $res[$timezone] = static::convertToHours(60 * $diff);
        }

        return $res;
    }

    /**
     * @param $probability
     *
     * @return int|string
     */
    public static function getDistributionIndex($probability)
    {
        $arr = [];
        for ($i = 0; $i < count($probability) - 1; $i++) {
            $arr[] = 0;
        }

        $sump = 0;
        foreach ($probability as $value) {
            $sump += $value;
        }
        $r = (mt_rand(0, 100) / 100) * $sump;
        $num = count($probability);
        foreach ($probability as $i => $value) {
            if ($r > $probability[$i]) {
                $r -= $probability[$i];
            } else {
                $num = $i;
                break;
            }
        }

        return $num;
    }

    public static function getDatesByWeek($_week_number, $_year = null)
    {
        $year = $_year
            ? $_year
            : date('Y');
        $week_number = sprintf('%02d', $_week_number);
        $date_base = strtotime($year . 'W' . $week_number . '1 00:00:00');
        $date_limit = strtotime($year . 'W' . $week_number . '7 23:59:59');
        if ($date_limit > time()) {
            $date_limit = time();
        }

        return date('d.m.Y', $date_base) . '-' . date('d.m.Y', $date_limit);
    }

    public static function getDatePartsFromSeconds($seconds)
    {
        $parts = ['d' => 0, 'h' => 0, 'm' => 0, 's' => 0];

        $parts['d'] = (int)floor($seconds / 86400);
        $hourSeconds = $seconds % 86400;
        $parts['h'] = (int)floor($hourSeconds / 3600);
        $minuteSeconds = $hourSeconds % 3600;
        $parts['m'] = (int)floor($minuteSeconds / 60);
        $remainingSeconds = $minuteSeconds % 60;
        $parts['s'] = (int)ceil($remainingSeconds);

        return $parts;
    }

    public static function getSecondsFromDateParts($parts)
    {
        $seconds = 0;
        $seconds += (int)$parts['d'] * 86400;
        $seconds += (int)$parts['h'] * 3600;
        $seconds += (int)$parts['m'] * 60;
        $seconds += (int)$parts['s'];

        return $seconds;
    }

    public static function getSecondsTillEndOfDay()
    {
        $time = static::nowStamp();

        return static::endOfDayTimestamp($time, false) - $time;
    }

    /**
     * @param int $seconds
     * @param bool $addSeconds
     * @param bool $daysToHours
     * @return string
     */
    public static function getTimeFromSeconds($seconds, $addSeconds = true, $daysToHours = false)
    {
        $parts = static::getDatePartsFromSeconds($seconds);

        $hours = $parts['h'];
        $hours += $daysToHours
            ? 24 * floor($seconds / (24 * 3600))
            : 0;

        $hours = strlen($hours) >= 2
            ? $hours
            : '0' . $hours;
        $minutes = strlen($parts['m']) >= 2
            ? $parts['m']
            : '0' . $parts['m'];
        $seconds = strlen($parts['s']) >= 2
            ? $parts['s']
            : '0' . $parts['s'];

        $timeParts = [$hours, $minutes];
        if ($addSeconds) {
            $timeParts[] = $seconds;
        }

        return join(':', $timeParts);
    }

    public static function getDaysAndTimeFromSeconds($seconds, $addSeconds = true)
    {
        $parts = static::getDatePartsFromSeconds($seconds);
        $res = static::getTimeFromSeconds($seconds, $addSeconds);
        $res = str_pad($parts['d'], 2, '0', STR_PAD_LEFT) . 'd. ' . $res;

        return $res;
    }

    /**
     * Return time close to N minute interval
     *
     * @param integer $minutes - Minutes to calculate
     * @return int
     */
    public static function getRoundedTime($minutes)
    {
        $seconds = time();
        $rounded_seconds = floor($seconds / ($minutes * 60)) * ($minutes * 60);

        return $rounded_seconds;
    }

    /**
     * Return time close to N second interval
     *
     * @param integer $second - Second
     * @return int
     */
    public static function getRoundedTimeSeconds($second)
    {
        $seconds = time();
        $rounded_seconds = floor($seconds / ($second)) * ($second);

        return $rounded_seconds;
    }

    /**
     * Метод, корректно генерирующий строку даты-времени с микросекундами и временной зоной в формате Elasticsearch.
     *
     * Не верьте официальной документации PHP, что DateTime::format('u') поддерживает микросекунды.
     * Ничего он не поддерживает уже много лет. Получить микросекунды можно только из microtime().
     *
     * Создавать объект DateTime с указанием таймштампа в конструкторе (через '@') нельзя.
     * При этом теряется информация о временной зоне. Таймштамп нужно задавать уже после создания объекта.
     *
     * @return string
     */
    public static function createElasticTimestamp()
    {
        $micro = microtime(true);
        $timestamp = floor($micro);
        $micro = floor(($micro - $timestamp) * 1000000);
        $datetime = new \DateTime();
        $datetime->setTimestamp($timestamp);

        return $datetime->format('Y-m-d\TH:i:s.') . sprintf('%06d', $micro) . $datetime->format('O');
    }

    /**
     * @param $value
     * @return bool
     */
    public static function checkUnits($value)
    {
        $value = (string)$value;

        return in_array($value, [self::HOURS, self::MINUTES, self::SECONDS]);
    }

    /**
     * Отдает абсолютное значение границ в секундах
     * Абсолютное, в данном контексте означает что мы всегда можем получить
     * размер промежутка простым вычитанием to - from
     *
     * @param \DateTime $from
     * @param \DateTime $to
     * @return int[]
     */
    public static function getPeriodBoundsInSec(\DateTime $from, \DateTime $to)
    {
        if ($to < $from) {
            throw new \InvalidArgumentException('$to must be greater than $from');
        }

        $fromSec = ((int)$from->format('H') * 3600) + ((int)$from->format('i') * 60) + ((int)$from->format('s'));
        $interval = $to->diff($from);
        $toSec = $fromSec + ($interval->d * 24 * 3600) + ($interval->h * 3600) + ($interval->i * 60) + $interval->s;

        return [$fromSec, $toSec];
    }


    /**
     * [start, end] passed month
     * Example for 2017, 7 -> [2017-07-01 00:00:00, 2017-08-01 00:00:00]
     *
     * @param int $year
     * @param int $month
     * @return \DateTime[] [$from, $to]
     */
    public static function getMonthAsPeriod($year, $month)
    {
        $from = \DateTime::createFromFormat('Y-m', $year . '-' . $month);
        $to = clone $from;

        $from->modify('first day of');
        $from->modify('today');

        $to->modify('last day of');
        $to->modify('+ 1 day today');

        return [$from, $to];
    }

    /**
     * @param int $weeksAgo
     * @param \DateTime|null $now
     * @return \DateTime
     */
    public static function getStartOfWeek($weeksAgo = 1, \DateTime $now = null)
    {
        if ($weeksAgo < 1) {
            throw new \InvalidArgumentException('1 week ago correspond current week and min valid value');
        }

        if (null === $now) {
            $now = new \DateTime();
        }

        $now->modify('this week today');
        $weeksAgo--;

        return $now->modify(sprintf('- %d weeks', $weeksAgo));
    }

    /**
     * @param \DateTime|null $dateTime
     * @return \DateTime
     */
    public static function getStartOfQuarter(\DateTime $dateTime = null)
    {
        if (null === $dateTime) {
            $dateTime = new \DateTime();
        }

        $year = $dateTime->format('Y');
        $month = $dateTime->format('n');

        $monthInQuarter = 3;

        $quarterNumber = (int)ceil($month / $monthInQuarter);
        $startMonthOfQuarter = ($quarterNumber * $monthInQuarter) - 2;

        return new \DateTime(sprintf('%s-%s-01', $year, $startMonthOfQuarter));
    }

    /**
     * @return \DateTime
     */
    public static function getStartOfToday()
    {
        return new \DateTime('00:00:00');
    }

    /**
     * @return \DateTime
     */
    public static function getStartOfMonth()
    {
        return new \DateTime('first day of this month 00:00:00');
    }

    /**
     * @return \DateTime
     */
    public static function getLast24Hour()
    {
        return new \DateTime('-24 hour');
    }

    /**
     * @return \DateTime
     */
    public static function getLastHour()
    {
        return new \DateTime('-1 hour');
    }

    /**
     * @param \DateTime|null $dateTime
     * @return int
     */
    public static function getQuarterNumber(\DateTime $dateTime = null)
    {
        if (null === $dateTime) {
            $dateTime = new \DateTime();
        }

        $month = $dateTime->format('n');

        return (int)ceil($month / self::MONTHS_IN_QUARTER);
    }

    /**
     * @param float | int $int
     * @return string
     * int 1.98 => str 1:59
     */
    public static function floatToTime($int)
    {
        return sprintf('%02d:%02d', (int)$int, fmod($int, 1) * 60);
    }
}
