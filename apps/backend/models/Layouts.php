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
 * Class Layouts
 * @package Multiple\Backend\Models
 */
class Layouts extends \Phalcon\Mvc\Model {

    public function initialize() {
        $this->hasOne("layout_id", "Multiple\Backend\Models\Blogs", "layout_id");
    }

    /**
     * Insere os dados do layout no banco de dados
     * @return boolean
     */
    public function createLayout() {
        $layout = new Layouts();
        //Caso não seja passado os dados do layout, cria um padrão
        $layout->layout_banner = '';
        $layout->layout_subtitle = addslashes(htmlentities('Page Heading<small>Secondary Text</small>'));
        $layout->layout_navbar = addslashes(htmlentities('<ul class="nav navbar-nav">
                                        <li>
                                            <a href="#" data-id="menu1" id="menu1">Menu 1</a>
                                        </li>
                                        <li>
                                            <a href="#" data-id="menu2" id="menu2">Menu 2</a>
                                        </li>
                                        <li>
                                            <a href="#" data-id="menu3" id="menu3">Menu 3</a>
                                        </li>
                                        <li>
                                            <a href="#" data-id="menu4" id="menu4">Menu 4</a>
                                        </li>
                                        <li>
                                            <a href="#" data-id="menu5" id="menu5">Menu 5</a>
                                        </li>
                                    </ul>'));
        $layout->layout_searchbar = addslashes(htmlentities('<h4>Buscar</h4>
                                    <div class="input-group">
                                        <input type="text" class="form-control">
                                        <span class="input-group-btn">
                                        <button class="btn btn-default" type="button">
                                        <span class="glyphicon glyphicon-search"></span>
                                        </button>
                                        </span>
                                    </div>'));
        $layout->layout_lateralbar = addslashes(htmlentities('<h4>Side Widget Well</h4>
            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Inventore, perspiciatis adipisci accusamus laudantium odit aliquam repellat tempore quos aspernatur vero.</p>'));
        $layout->layout_navigation = addslashes(htmlentities('<li class="previous">
                                                                <a href="#">&larr; Voltar</a>
                                                            </li>
                                                            <li class="next">
                                                                <a href="#">Avançar &rarr;</a>
                                                            </li>'));
        $layout->layout_footer = addslashes(htmlentities("<p>Copyright &copy; Your Website 2014</p>"));

        $layout->save();

        return $layout->layout_id;
    }

    /**
     * Busca um layout pelo id
     * @param  int $id_layout id do layout
     * @return array            array com os dados do layout retornado
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
            case 'navbar':
                $layout->layout_navbar = $content;
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