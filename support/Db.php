<?php
declare(strict_types=1);

namespace Support;

use Closure;
use Illuminate\Database\Capsule\Manager;

/**
 * Class Db
 *
 * @package Support
 * @method static array select(string $query, $bindings = [], $useReadPdo = true)
 * @method static int insert(string $query, $bindings = [])
 * @method static int update(string $query, $bindings = [])
 * @method static int delete(string $query, $bindings = [])
 * @method static bool statement(string $query, $bindings = [])
 * @method static mixed transaction(Closure $callback, $attempts = 1)
 * @method static void beginTransaction()
 * @method static void rollBack($toLevel = null)
 * @method static void commit()
 */
class Db extends Manager
{

}