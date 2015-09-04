<?php

    namespace Dez\Loader;

    /**
     * Class Loader
     * @package Dez\Loader
     */
    class Loader {

        /**
         * @var array
         */
        protected $namespaces   = [];

        /**
         * @var array
         */
        protected $classes      = [];

        /**
         * @var array
         */
        protected $prefixes     = [];

        /**
         * @var array
         */
        protected $directories  = [];

        /**
         * @var bool
         */
        protected $registered   = false;

        /**
         * @param $class
         * @return bool
         */
        protected function autoload( $class ) {

            $ns = '\\';
            $ds = DIRECTORY_SEPARATOR;

            // find in classes
            if( count( $this->classes ) > 0 && isset( $this->classes[ $class ] ) ) {
                include_once $this->classes[ $class ];
                return true;
            }

            // find in namespaces
            if( count( $this->namespaces ) > 0 ) {
                foreach( $this->namespaces as $namespace => $directory ) {
                    if( strpos( $class, $namespace ) === 0 ) {

                        $fileName   = substr( $class, strlen( $namespace . $ns ) );
                        $fileName   = str_replace( $ns, $ds, $fileName );

                        $fileName   = "$fileName.php";

                        $filePath   = rtrim( $directory, $ds ) . $ds . $fileName;

                        if( file_exists( $filePath ) ) {
                            include_once $filePath;
                            return true;
                        }
                    }
                }
            }

            // find in prefixes
            if( count( $this->prefixes ) > 0 ) {
                foreach( $this->prefixes as $prefix => $directory ) {
                    if( strpos( $class, $prefix ) === 0 ) {

                        $fileName   = substr( $class, strlen( $prefix . '_' ) );
                        $fileName   = str_replace( '_', $ds, $fileName );

                        $fileName   = "$fileName.php";

                        $filePath   = rtrim( $directory, $ds ) . $ds . $fileName;

                        if( file_exists( $filePath ) ) {
                            include_once $filePath;
                            return true;
                        }
                    }
                }
            }

            // find in directories
            if( count( $this->directories ) > 0 ) {
                $fileName   = str_replace( $ns, $ds, str_replace( '_', $ds, $class ) );
                $fileName   = "$fileName.php";
                foreach( $this->directories as $directory ) {

                    $directory = rtrim( $directory, $ds ) . $ds;
                    $filePath   = $directory . $fileName;

                    if( file_exists( $filePath ) ) {
                        include_once $filePath;
                        return true;
                    }

                }
            }

            return false;

        }

        /**
         * @param array $classes
         * @return $this
         */
        public function registerClasses( array $classes = [] ) {
            if( count( $this->classes ) == 0 ) {
                $this->classes  = $classes;
            } else {
                $this->classes  = array_merge( $this->classes, $classes );
            }
            $this->registered   = false;
            return $this;
        }

        /**
         * @param array $prefixes
         * @return $this
         */
        public function registerPrefixes( array $prefixes = [] ) {
            if( count( $this->prefixes ) == 0 ) {
                $this->prefixes  = $prefixes;
            } else {
                $this->prefixes  = array_merge( $this->prefixes, $prefixes );
            }
            $this->registered   = false;
            return $this;
        }

        /**
         * @param array $directories
         * @return $this
         */
        public function registerDirectories( array $directories = [] ) {
            if( count( $this->directories ) == 0 ) {
                $this->directories  = $directories;
            } else {
                $this->directories  = array_merge( $this->directories, $directories );
            }
            $this->registered   = false;
            return $this;
        }

        /**
         * @param array $namespaces
         * @return $this
         */
        public function registerNamespaces( array $namespaces = [] ) {
            if( count( $this->namespaces ) == 0 ) {
                $this->namespaces  = $namespaces;
            } else {
                $this->namespaces  = array_merge( $this->namespaces, $namespaces );
            }
            $this->registered   = false;
            return $this;
        }

        /**
         *
         */
        public function register() {
            if( ! $this->isRegistered() ) {
                spl_autoload_register( [ $this, 'autoload' ] );
                $this->registered   = true;
            }
        }

        /**
         * @return array
         */
        public function getNamespaces() {
            return $this->namespaces;
        }

        /**
         * @return array
         */
        public function getClasses() {
            return $this->classes;
        }

        /**
         * @return array
         */
        public function getPrefixes() {
            return $this->prefixes;
        }

        /**
         * @return array
         */
        public function getDirectories() {
            return $this->directories;
        }

        /**
         * @return boolean
         */
        public function isRegistered() {
            return $this->registered;
        }

    }