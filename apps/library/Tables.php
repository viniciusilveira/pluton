<?php

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
                        )
                ),
                new Column("layout_banner", array(
                    "type" => Column::TYPE_VARCHAR,
                    "size" => 200,
                    "notNull" => false
                        )
                ),
                new Column("layout_background_color", array(
                    "type" => Column::TYPE_VARCHAR,
                    "size" => 7,
                    "notNull" => true
                        )
                ),
                new Column("layout_font_color", array(
                    "type" => Column::TYPE_VARCHAR,
                    "size" => 7,
                    "notNull" => true
                        )
                ),
                new Column("layout_active", array(
                    "type" => Column::TYPE_BOOLEAN
                        )
                ),
                new Column("layout_menu1", array(
                    "type" => Column::TYPE_VARCHAR,
                    "size" => 20,
                    "notNull" => true
                        )
                ),
                new Column("layout_menu2", array(
                    "type" => Column::TYPE_VARCHAR,
                    "size" => 20,
                    "notNull" => true
                        )
                ),
                new Column("layout_menu3", array(
                    "type" => Column::TYPE_VARCHAR,
                    "size" => 20,
                    "notNull" => true
                        )
                ),
                new Column("layout_menu4", array(
                    "type" => Column::TYPE_VARCHAR,
                    "size" => 20,
                    "notNull" => true
                        )
                ),
                new Column("layout_menu5", array(
                    "type" => Column::TYPE_VARCHAR,
                    "size" => 20,
                    "notNull" => true
                        )
                ),
                new Column("layout_footer", array(
                    "type" => Column::TYPE_VARCHAR,
                    "size" => 200,
                    "notNull" => false
                        )
                )
            )
        );
        $connection->createTable("Layouts", NULL, $table);
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
                        )
                ),
                new Column("blog_name", array(
                    "type" => Column::TYPE_VARCHAR,
                    "size" => 50,
                    "notNull" => true,
                        )
                ),
                new Column("blog_layout", array(
                    "type" => Column::TYPE_INTEGER,
                    "size" => 10,
                    "notNull" => true,
                        )
                ),
            ),
            "indexes" => array(
                new Index(
                    "blog_layout", array("blog_layout")
                )
            ),
            "references" => array(
                new Reference(
                    "blog_fk_layout", array(
                        "referencedTable" => "layouts",
                        "columns" => array("blog_layout"),
                        "referencedColumns" => array("layout_id"),
                    )
                )
            )
        );
        $connection->createTable("Blogs", NULL, $table);
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
                    "autoIncrement" => true,
                        )
                ),
                new Column("user_name", array(
                    "type" => Column::TYPE_VARCHAR,
                    "size" => 250,
                    "notNull" => true
                        )
                ),
                new Column("user_login", array(
                    "type" => Column::TYPE_VARCHAR,
                    "size" => 20,
                    "notNull" => true
                        )
                ),
                new Column("user_email", array(
                    "type" => Column::TYPE_VARCHAR,
                    "size" => 50,
                    "notNull" => true
                        )
                ),
                new Column("user_passwd", array(
                    "type" => Column::TYPE_VARCHAR,
                    "size" => 250,
                    "notNull" => true
                        )
                ),
                new Column("user_type", array(
                    "type" => Column::TYPE_VARCHAR,
                    "notNull" => true,
                    "size" => 2
                        )
                ),
                new Column("user_blog", array(
                    "type" => Column::TYPE_INTEGER,
                    "size" => 10
                        )
                ),
                new Column("user_img", array(
                    "type" => Column::TYPE_VARCHAR,
                    "size" => 150
                        )
                )
            )
        );
        $connection->createTable("Users", NULL, $table);
    }

    /**
     * Cria a tabela users_blogs
     * @param $connection => Variável de conexão com o banco de dados
     */
    public function createTableUsersBlogs($connection){
        $table = array(
            "columns" => array(
                new Column("user_blog_id", array(
                    "type" => Column::TYPE_INTEGER,
                    "primary" => true,
                    "size" => 10,
                    "notNull" => true,
                    "autoIncrement" => true,
                    )
                ),
                new Column("blog_id", array(
                        "type" => Column::TYPE_INTEGER,
                        "size" => 10,
                        "notNull" => true
                        )
                ),
                new Column("user_id", array(
                        "type" => Column::TYPE_INTEGER,
                        "size" => 10,
                        "notNull" => true
                    )
                )
            ),
            "indexes" => array(
                new Index("blog_id", array("blog_id")
                ),
                new Index("user_id", array("user_id")
                )
            ),
            "references" => array(
                new Reference("blog_fk_user_blog", array(
                        "referencedTable" => "blogs",
                        "columns" => array("blog_id"),
                        "referencedColumns" => array("blog_id"),
                    )
                ),
                new Reference("user_fk_user_blog", array(
                        "referencedTable" => "users",
                        "columns" => array("user_id"),
                        "referencedColumns" => array("user_id"),
                    )
                )
            )
        );
        $connection->createTable("Users_blogs", NULL, $table);
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
                        )
                ),
                new Column("post_blog", array(
                    "type" => Column::TYPE_INTEGER,
                    "size" => 10,
                    "notNull" => true
                        )
                ),
                new Column("post_date", array(
                    "type" => Column::TYPE_DATETIME,
                    "notNull" => true
                        )
                ),
                new Column("post_author", array(
                    "type" => Column::TYPE_INTEGER,
                    "notNull" => true
                        )
                ),
                new Column("post_editor", array(
                    "type" => Column::TYPE_INTEGER,
                    "notNull" => true
                        )
                ),
                new Column("post_change_date", array(
                    "type" => Column::TYPE_DATETIME
                        )
                ),
                new Column("post_title", array(
                    "type" => Column::TYPE_VARCHAR,
                    "size" => 150,
                    "notNull" => true
                        )
                ),
                new Column("post_content", array(
                    "type" => Column::TYPE_TEXT,
                    "notNull" => true
                        )
                )
            ),

            "indexes" => array(
                new Index(
                        "post_author", array("post_author")
                ),
                new Index(
                        "post_editor", array("post_editor")
                )
            ),
            "references" => array(
                new Reference(
                        "author_fk_post", array(
                    "referencedTable" => "users",
                    "columns" => array("post_author"),
                    "referencedColumns" => array("user_id"),
                        )
                ),
                new Reference(
                        "editor_fk_post", array(
                    "referencedTable" => "users",
                    "columns" => array("post_editor"),
                    "referencedColumns" => array("user_id"),
                        )
                )
            )
        );
        $connection->createTable("Posts", NULL, $table);
    }

    /**
     * Cria a tabela social_network
     * @param $connection => Variável de conexão com o banco de dados
     */
    public function createTableSocialNetwork($connection){
        $table = array(
            "columns" => array(
                new Column("social_network_id", array(
                        "type" => Column::TYPE_INTEGER,
                        "primary" => true,
                        "size" => 10,
                        "notNull" => true,
                        "autoIncrement" => true,
                    )
                ),
                new Column("social_network_name", array(
                        "type" => Column::TYPE_VARCHAR,
                        "size" => 50,
                        "notNull" => true,
                    )
                ),
                new Column("social_network_login", array(
                        "type" => Column::TYPE_VARCHAR,
                        "size" => 20,
                        "notNull" => true,
                    )
                ),
                new Column("social_network_passwd", array(
                        "type" => Column::TYPE_VARCHAR,
                        "size" => 20,
                        "notNull" => true,
                    )
                ),
                new Column("social_network_blog", array(
                        "type" => Column::TYPE_INTEGER,
                        "size" => 10,
                        "notNull" => true
                    )
                )
            ),
            "indexes" => array(
                new Index(
                    "social_network_blog", array("social_network_blog")
                )
            ),
            "references" => array(
                new Reference(
                    "social_network_fk_blog", array(
                        "referencedTable" => "blogs",
                        "columns" => array("social_network_blog"),
                        "referencedColumns" => array("blog_id"),
                    )
                )
            )
        );
        $connection->createTable("Social_network", NULL, $table);
    }
}
