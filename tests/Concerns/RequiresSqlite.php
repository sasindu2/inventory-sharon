<?php

namespace Tests\Concerns;

use PDO;

trait RequiresSqlite
{
    protected function prepareSqliteDatabase(): void
    {
        if (! in_array('sqlite', PDO::getAvailableDrivers(), true)) {
            $this->markTestSkipped('The pdo_sqlite driver is not available in this PHP environment.');
        }

        $this->artisan('migrate:fresh');
    }
}
