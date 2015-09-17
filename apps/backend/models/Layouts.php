<?php
/**
 * Class and Function List:
 * Function list:
 * - initialize()
 * - createLayout()
 * - updateLayout()
 * Classes list:
 * - Layouts extends \
 */
namespace Multiple\Backend\Models;

/**
 * Classe responsável por manipular dados referentes ao Layout do blog
 */
class Layouts extends \Phalcon\Mvc\Model {

    public function initialize() {
        $this->hasOne("layout_id", "Multiple\Backend\Models\Blogs", "layout_id");
    }

    /**
     * Insere os dados do layout no banco de dados
     * @return boolean verdadeiro caso sucesso ou falso caso ocorra algum erro
     */
    public function createLayout() {
        $layout = new Layouts();

        //Caso não seja passado os dados do layout, cria um padrão
        $layout->layout_banner = '';
        $layout->layout_subtitle = addslashes(htmlentities('Page Heading<small>Secondary Text</small>'));
        $layout->layout_menu1 = "Home";
        $layout->layout_menu2 = "Sobre";
        $layout->layout_menu3 = "Contato";
        $layout->layout_searchbar = addslashes(htmlentities('<h4>Buscar</h4>
                                    <div class="input-group">
                                        <input type="text" id="data-search" name="data-search" class="form-control">
                                        <span class="input-group-btn">
                                        <button id="search" class="btn btn-default" type="button">
                                        <span class="glyphicon glyphicon-search"></span>
                                        </button>
                                        </span>
                                    </div>'));
        $layout->layout_lateralbar = addslashes(htmlentities('<h4>Side Widget Well</h4>
            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Inventore, perspiciatis adipisci accusamus laudantium odit aliquam repellat tempore quos aspernatur vero.</p>'));

        $layout->layout_footer = addslashes(htmlentities("<p>Copyright &copy; Your Website " . date('Y') . "</p>"));

        $layout->save();

        return $layout->layout_id;
    }

    /**
     * Atualiza os dados de uma deterimanda parte do layout
     * @param  string $attribute parte do layout a ser alterada
     * @param  string $content   novo conteúdo a ser inserido
     * @return boolean            Verdadeiro caso sucesso ou falso caso ocorra algum erro
     */
    public function updateLayout($attribute, $content) {
        $content = addslashes(htmlentities($content));
        $layout = Layouts::findFirst();

        switch ($attribute) {
            case 'title':
                $layout->layout_title = $content;
                return $layout->save();
            break;
            case 'subtitle':
                $layout->layout_subtitle = $content;
                return $layout->save();
            case 'home':
                $layout->layout_menu1 = $content;
                return $layout->save();
            case 'about':
                $layout->layout_menu2 = $content;
                return $layout->save();
            case 'contact':
                $layout->layout_menu3 = $content;
                return $layout->save();
            break;
            case 'lateralbar':
                $layout->layout_lateralbar = $content;
                return $layout->save();
            break;
            case 'searchbar':
                $layout->layout_searchbar = $content;
                return $layout->save();
            break;
            case 'footer':
                $layout->layout_footer = $content;
                return $layout->save();
            break;
            case 'navigation':
                $layout->layout_navigation = $content;
                return $layout->save();
            break;
            default:
                return false;
            break;
        }
    }
}
