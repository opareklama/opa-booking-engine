<?php
declare(strict_types=1);

namespace OpaReklama\Booking\Bootstrap;

use Exception;
use ReflectionClass;

/**
 * A lightweight Dependency Injection Container.
 * Manages class dependencies automatically using reflection.
 */
class Container {
    /**
     * @var array Instances of resolved classes.
     */
    private array $instances = [];

    /**
     * Resolve a class instance.
     *
     * @param string $class_name
     * @return object
     * @throws Exception
     */
    public function get( string $class_name ): object {
        if ( isset( $this->instances[ $class_name ] ) ) {
            return $this->instances[ $class_name ];
        }

        $instance = $this->resolve( $class_name );
        $this->instances[ $class_name ] = $instance;

        return $instance;
    }

    /**
     * Bind an existing instance to the container.
     *
     * @param string $class_name
     * @param object $instance
     */
    public function singleton( string $class_name, object $instance ): void {
        $this->instances[ $class_name ] = $instance;
    }

    /**
     * Automatically resolve dependencies via Reflection.
     *
     * @param string $class_name
     * @return object
     * @throws Exception
     */
    private function resolve( string $class_name ): object {
        if ( ! class_exists( $class_name ) ) {
            throw new Exception( "Class {$class_name} does not exist." );
        }

        $reflector = new ReflectionClass( $class_name );

        if ( ! $reflector->isInstantiable() ) {
            throw new Exception( "Class {$class_name} is not instantiable." );
        }

        $constructor = $reflector->getConstructor();

        if ( is_null( $constructor ) ) {
            return new $class_name();
        }

        $parameters = $constructor->getParameters();
        $dependencies = [];

        foreach ( $parameters as $parameter ) {
            $type = $parameter->getType();

            if ( ! $type || $type->isBuiltin() ) {
                if ( $parameter->isDefaultValueAvailable() ) {
                    $dependencies[] = $parameter->getDefaultValue();
                } else {
                    throw new Exception( "Cannot resolve non-class dependency {$parameter->getName()} in {$class_name}." );
                }
            } else {
                $dependencies[] = $this->get( $type->getName() );
            }
        }

        return $reflector->newInstanceArgs( $dependencies );
    }
}
