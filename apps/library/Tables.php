<?php
/**
 * Class and Function List:
 * Function list:
 * - createTableParameters()
 * - createTableLayouts()
 * - createTableBlogs()
 * - createTableUserType()
 * - createTableUsers()
 * - createTableUsersBlogs()
 * - createTablePosts()
 * - createTableSocialNetwork()
 * Classes list:
 * - Tables
 */
namespace Multiple\Library;

use Phalcon\Db\Column as Column;
use Phalcon\Db\Index as Index;
use Phalcon\Db\Reference as Reference;

/**
 * Class Tables
 * Library Responsável por criar todas as tabelas necessárias para o projeto.
 * @package Multiple\Library
 */
class Tables {

    public function createTableParameters($connection) {
        $table = array(
            "columns" => array(
                new Column("parameter_id", array(
                    "type" => Column::TYPE_INTEGER,
                    "primary" => true,
                    "size" => 10,
                    "notNull" => true,
                    "autoIncrement" => true,
                )) ,
                new Column("parameter_description", array(
                    "type" => Column::TYPE_TEXT,
                    "notNull" => true
                )) ,
                new Column("parameter_value", array(
                    "type" => Column::TYPE_TEXT,
                    "notNull" => true
                ))
            )
        );

        $connection->createTable("parameters", NULL, $table);
    }

    /**
     * Cria a tabela Layouts
     * @param $connection => variável de conexão com o banco de dados
     */
    public function createTableLayouts($connection) {

        $table = array(
            "columns" => array(
                new Column("layout_id", array(
                    "type" => Column::TYPE_INTEGER,
                    "primary" => true,
                    "size" => 10,
                    "notNull" => true,
                    "autoIncrement" => true,
                )) ,
                new Column("layout_banner", array(
                    "type" => Column::TYPE_VARCHAR,
                    "size" => 200,
                    "notNull" => false
                )) ,
                new Column("layout_title", array(
                    "type" => Column::TYPE_VARCHAR,
                    "size" => 500,
                    "notNull" => true
                )),

                new Column("layout_subtitle", array(
                    "type" => Column::TYPE_VARCHAR,
                    "size" => 500,
                    "notNull" => true
                )),
                new Column("layout_navbar", array(
                    "type" => Column::TYPE_VARCHAR,
                    "size" => 65535,
                    "notNull" => true
                )),
                new Column("layout_lateralbar", array(
                    "type" => Column::TYPE_VARCHAR,
                    "size" => 65535,
                    "notNull" => true
                )),
                new Column("layout_searchbar", array(
                    "type" => Column::TYPE_VARCHAR,
                    "size" => 65535,
                    "notNull" => true
                )),
                new Column("layout_navigation", array(
                    "type" => Column::TYPE_VARCHAR,
                    "size" => 65535,
                    "notNull" => true
                )),
                new Column("layout_footer", array(
                    "type" => Column::TYPE_VARCHAR,
                    "size" => 65535,
                    "notNull" => true
                ))
            )
        );
        $connection->createTable("layouts", NULL, $table);
    }

    /**
     * Cria a tabela blogs
     * @param $connection => Variável de conexão com o banco de dados
     */
    public function createTableBlogs($connection) {

        $table = array(
            "columns" => array(
                new Column("blog_id", array(
                    "type" => Column::TYPE_INTEGER,
                    "primary" => true,
                    "size" => 10,
                    "notNull" => true,
                    "autoIncrement" => true,
                )) ,
                new Column("blog_name", array(
                    "type" => Column::TYPE_VARCHAR,
                    "size" => 50,
                    "notNull" => true,
                )) ,
                new Column("blog_layout", array(
                    "type" => Column::TYPE_INTEGER,
                    "size" => 10,
                    "notNull" => true,
                )) ,
            ) ,
            "indexes" => array(
                new Index("blog_layout", array(
                    "blog_layout"
                ))
            ) ,
            "references" => array(
                new Reference("blog_fk_layout", array(
                    "referencedTable" => "layouts",
                    "columns" => array(
                        "blog_layout"
                    ) ,
                    "referencedColumns" => array(
                        "layout_id"
                    ) ,
                ))
            )
        );
        $connection->createTable("blogs", NULL, $table);
    }

    /**
     * Cria a tabela user_type
     * @param  $connection Variável de conexão com o banco de dados
     *
     * @todo: Verificar campos necessários e implementar construção da tabela
     */
    public function createTableUserType($connection) {
        $table = array(
            "columns" => array(
                new Column("user_type_id", array(
                    "type" => Column::TYPE_INTEGER,
                    "primary" => true,
                    "size" => 10,
                    "notNull" => true,
                    "autoIncrement" => true
                )) ,
                new Column("user_type_descr", array(
                    "type" => Column::TYPE_VARCHAR,
                    "size" => 250,
                    "notNull" => true
                )) ,
                new Column("user_type_abrev", array(
                    "type" => Column::TYPE_VARCHAR,
                    "size" => 10,
                    "notNull" => true
                ))
            )
        );
        $connection->createTable("user_type", NULL, $table);
    }

    /**
     * Cria a tabela users
     * @param $connection => Variável de conexão com o banco de dados
     */
    public function createTableUsers($connection) {

        $table = array(
            "columns" => array(
                new Column("user_id", array(
                    "type" => Column::TYPE_INTEGER,
                    "primary" => true,
                    "size" => 10,
                    "notNull" => true,
                    "autoIncrement" => true
                )) ,
                new Column("user_name", array(
                    "type" => Column::TYPE_VARCHAR,
                    "size" => 250,
                    "notNull" => true
                )) ,
                new Column("user_login", array(
                    "type" => Column::TYPE_VARCHAR,
                    "size" => 20,
                    "notNull" => true
                )) ,
                new Column("user_email", array(
                    "type" => Column::TYPE_VARCHAR,
                    "size" => 50,
                    "notNull" => true
                )) ,
                new Column("user_passwd", array(
                    "type" => Column::TYPE_VARCHAR,
                    "size" => 250,
                    "notNull" => true
                )) ,
                new Column("user_type_id", array(
                    "type" => Column::TYPE_INTEGER,
                    "size" => 10
                )) ,
                new Column("user_blog", array(
                    "type" => Column::TYPE_INTEGER,
                    "size" => 10
                )) ,
                new Column("user_img", array(
                    "type" => Column::TYPE_VARCHAR,
                    "size" => 150
                )) ,
                new Column("user_active", array(
                    "type" => Column::TYPE_BOOLEAN,
                    "notNull" => true
                )) ,
            ) ,
            "indexes" => array(
                new Index("user_type_id", array(
                    "user_type_id"
                )) ,
            ) ,
            "references" => array(
                new Reference("user_fk_user_type", array(
                    "referencedTable" => "user_type",
                    "columns" => array(
                        "user_type_id"
                    ) ,
                    "referencedColumns" => array(
                        "user_type_id"
                    )
                ))
            )
        );
        $connection->createTable("users", NULL, $table);
    }

    /**
     * Cria a tabela users_blogs
     * @param $connection => Variável de conexão com o banco de dados
     */
    public function createTableUsersBlogs($connection) {
        $table = array(
            "columns" => array(
                new Column("user_blog_id", array(
                    "type" => Column::TYPE_INTEGER,
                    "primary" => true,
                    "size" => 10,
                    "notNull" => true,
                    "autoIncrement" => true
                )) ,
                new Column("blog_id", array(
                    "type" => Column::TYPE_INTEGER,
                    "size" => 10,
                    "notNull" => true
                )) ,
                new Column("user_id", array(
                    "type" => Column::TYPE_INTEGER,
                    "size" => 10,
                    "notNull" => true
                ))
            ) ,
            "indexes" => array(
                new Index("blog_id", array(
                    "blog_id"
                )) ,
                new Index("user_id", array(
                    "user_id"
                ))
            ) ,
            "references" => array(
                new Reference("blog_fk_user_blog", array(
                    "referencedTable" => "blogs",
                    "columns" => array(
                        "blog_id"
                    ) ,
                    "referencedColumns" => array(
                        "blog_id"
                    ) ,
                )) ,
                new Reference("user_fk_user_blog", array(
                    "referencedTable" => "users",
                    "columns" => array(
                        "user_id"
                    ) ,
                    "referencedColumns" => array(
                        "user_id"
                    ) ,
                ))
            )
        );
        $connection->createTable("users_blogs", NULL, $table);
    }

    /**
     * Cria a tabela posts
     * @param $connection => Variável de conexão com o banco de dados
     */
    public function createTablePosts($connection) {
        $table = array(
            "columns" => array(
                new Column("post_id", array(
                    "type" => Column::TYPE_INTEGER,
                    "primary" => true,
                    "size" => 10,
                    "notNull" => true,
                    "autoIncrement" => true,
                )) ,
                new Column("post_blog", array(
                    "type" => Column::TYPE_INTEGER,
                    "size" => 10,
                    "notNull" => true
                )) ,
                new Column("post_date", array(
                    "type" => Column::TYPE_DATETIME,
                    "notNull" => true
                )) ,
                new Column("post_author", array(
                    "type" => Column::TYPE_INTEGER,
                    "notNull" => true
                )) ,
                new Column("post_editor", array(
                    "type" => Column::TYPE_INTEGER,
                    "notNull" => true
                )) ,
                new Column("post_change_date", array(
                    "type" => Column::TYPE_DATETIME
                )) ,
                new Column("post_title", array(
                    "type" => Column::TYPE_VARCHAR,
                    "size" => 150,
                    "notNull" => true
                )) ,
                new Column("post_content", array(
                    "type" => Column::TYPE_TEXT,
                    "notNull" => true
                ))
            ) ,

            "indexes" => array(
                new Index("post_author", array(
                    "post_author"
                )) ,
                new Index("post_editor", array(
                    "post_editor"
                ))
            ) ,
            "references" => array(
                new Reference("author_fk_post", array(
                    "referencedTable" => "users",
                    "columns" => array(
                        "post_author"
                    ) ,
                    "referencedColumns" => array(
                        "user_id"
                    ) ,
                )) ,
                new Reference("editor_fk_post", array(
                    "referencedTable" => "users",
                    "columns" => array(
                        "post_editor"
                    ) ,
                    "referencedColumns" => array(
                        "user_id"
                    ) ,
                ))
            )
        );
        $connection->createTable("posts", NULL, $table);
    }

    /**
     * Cria a tabela social_network
     * @param $connection => Variável de conexão com o banco de dados
     */
    public function createTableSocialNetwork($connection) {
        $table = array(
            "columns" => array(
                new Column("social_network_id", array(
                    "type" => Column::TYPE_INTEGER,
                    "primary" => true,
                    "size" => 10,
                    "notNull" => true,
                    "autoIncrement" => true,
                )) ,
                new Column("social_network_name", array(
                    "type" => Column::TYPE_VARCHAR,
                    "size" => 50,
                    "notNull" => true,
                )) ,
                new Column("social_network_login", array(
                    "type" => Column::TYPE_VARCHAR,
                    "size" => 20,
                    "notNull" => true,
                )) ,
                new Column("social_network_passwd", array(
                    "type" => Column::TYPE_VARCHAR,
                    "size" => 20,
                    "notNull" => true,
                )) ,
                new Column("social_network_blog", array(
                    "type" => Column::TYPE_INTEGER,
                    "size" => 10,
                    "notNull" => true
                ))
            ) ,
            "indexes" => array(
                new Index("social_network_blog", array(
                    "social_network_blog"
                ))
            ) ,
            "references" => array(
                new Reference("social_network_fk_blog", array(
                    "referencedTable" => "blogs",
                    "columns" => array(
                        "social_network_blog"
                    ) ,
                    "referencedColumns" => array(
                        "blog_id"
                    ) ,
                ))
            )
        );
        $connection->createTable("social_network", NULL, $table);
    }
}
