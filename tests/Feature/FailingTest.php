<?php

namespace Tests\Feature;

use PHPUnit\Framework\TestCase;

class FailingTest extends TestCase
{
    /**
     * Este test FALLA intencionalmente para verificar que el CI detecta fallos
     * y NO hace auto-merge cuando hay tests rotos.
     * 
     * Propósito: comprobar que el workflow NO mergea PRs con tests fallidos.
     */
    public function test_this_test_intentionally_fails()
    {
        // Este assertion fuerza un fallo para verificar que el CI funciona
        $this->assertTrue(false, 'Este test falla intencionalmente para probar que CI detecta fallos.');
    }
}
