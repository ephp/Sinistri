<?php

namespace Ephp\Bundle\DragDropBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class DragDropController extends Controller {

    /**
     * Inserisce il modulo per caricamento via Drag & Drop delle immagini
     *
     * @Route("/formjs", name="drag_drop_formjs")
     * @Method("post")
     * @Template("EphpDragDropBundle:DragDrop:formjs.html.php")
     */
    public function formjsAction() {
        return array('test' => false);
    }

    /**
     * Inserisce il modulo per caricamento via Drag & Drop delle immagini
     *
     * @Route("/single", name="drag_drop_single_row")
     * @Method("post")
     * @Template("EphpDragDropBundle:DragDrop:single.html.php")
     */
    public function singleAction() {
        $request = $this->getRequest();
        $field = $request->get('field', false);
        if (!$field) {
            throw new \Exception("Property 'field' required");
        }
        $id = $request->get('id', 'fileupload');
        $mimetype = $request->get('mimetype', false);
        $value = $request->get('value', false);
        $dir = $request->get('dir', '');
        $env = $request->get('env', false);
        $x = $request->get('resize_x', 100);
        $y = $request->get('resize_y', 100);
        $delete = false;
        $tmb = false;
        if (preg_match('/.(gif|jpe?g|png)$/i', $value)) {
            $tmb = str_replace('/files/', '/thumbnails/', $value);
            $file = str_replace('/uploads/files/', '', $value);
            $delete = '/upload.php?file=' . $file;
        }
        return array(
            'id' => $id,
            'field' => $field,
            'mimetype' => $mimetype,
            'value' => $value != false,
            'env' => $env,
            'dir' => $dir,
            'x' => $x,
            'y' => $y,
            'tmb' => $tmb,
            'delete' => $delete,
        );
    }

    /**
     * Inserisce il modulo per caricamento via Drag & Drop delle immagini
     *
     * @Route("/multi", name="drag_drop_multi_row")
     * @Method("post")
     * @Template("EphpDragDropBundle:DragDrop:multi.html.php")
     */
    public function multiAction() {
        $_tmb = array();
        $_values = array();
        $_didascalie = array();
        $_foto_id = array();
        $_delete = array();
        $request = $this->getRequest();
        $field = $request->get('field', false);
        if (!$field) {
            throw new \Exception("Property 'field' required");
        }
        $id = $request->get('id', 'fileupload');
        $mimetype = $request->get('mimetype', false);
        $values = $request->get('values', false);
        $didascalie = $request->get('didascalie', false);
        $foto_id = $request->get('foto_id', false);
        if ($values) {
            $values = json_decode($values);
            foreach ($values as $value) {
                if (preg_match('/.(gif|jpe?g|png)$/i', $value)) {
                    $_tmb[] = str_replace('/files/', '/thumbnails/', $value);
                    $_values[] = str_replace('/thumbnails/', '/files/', $value);
                    $file = str_replace('/uploads/files/', '', $value);
                    $_delete[] = '/upload.php?file=' . $file;
                }
            }
            $_didascalie = $didascalie ? json_decode($didascalie) : $_values;
            $_foto_id = $foto_id ? json_decode($foto_id) : $_values;
            if (!$foto_id) {
                for ($i = 0; $i < count($_foto_id); $i++) {
                    $_foto_id[$i] = '';
                }
            }
        }
        $dir = $request->get('dir', '');
        $env = $request->get('env', false);
        $x = $request->get('resize_x', 100);
        $y = $request->get('resize_y', 100);
        $delete = false;
        $tmb = false;
        return array(
            'id' => $id,
            'field' => $field,
            'mimetype' => $mimetype,
            'values' => json_encode($_values),
            'didascalie' => json_encode($_didascalie),
            'foto_id' => json_encode($_foto_id),
            'env' => $env,
            'dir' => $dir,
            'x' => $x,
            'y' => $y,
            'tmb' => json_encode($_tmb),
            'delete' => json_encode($_delete),
            'n' => count($_tmb),
        );
    }

    /**
     * Inserisce il modulo per caricamento via Drag & Drop delle immagini
     *
     * @Route("/single/professionista", name="drag_drop_single_professionista_row")
     * @Method("post")
     * @Template("EphpDragDropBundle:DragDrop:singleProfessionista.html.php")
     */
    public function singleProfessionistaAction() {
        $out = $this->singleAction();
        $out['dir'] = 'professionista/' . $out['dir'] . '/foto';
        $out['tmb'] = str_replace('/thumbnails/', '/avatar/', $out['tmb']);
        return $out;
    }

    /**
     * Inserisce il modulo per caricamento via Drag & Drop delle immagini
     *
     * @Route("/single/curriculum", name="drag_drop_single_curriculum_row")
     * @Method("post")
     * @Template("EphpDragDropBundle:DragDrop:curriculum.html.php")
     */
    public function curriculumAction() {
        $out = $this->singleAction();
        $out['dir'] = 'professionista/' . $out['dir'] . '/cv';
        return $out;
    }

    /**
     * Inserisce il modulo per caricamento via Drag & Drop delle immagini
     *
     * @Route("/single/bigprofessionista", name="drag_drop_single_bigprofessionista_row")
     * @Method("post")
     * @Template("EphpDragDropBundle:DragDrop:singleAzienda.html.php")
     */
    public function singleBigProfessionistaAction() {
        $out = $this->singleAction();
        $out['dir'] = 'professionista/' . $out['dir'] . '/foto';
        return $out;
    }

    /**
     * Inserisce il modulo per caricamento via Drag & Drop delle immagini
     *
     * @Route("/single/azienda", name="drag_drop_single_azienda_row")
     * @Method("post")
     * @Template("EphpDragDropBundle:DragDrop:singleAzienda.html.php")
     */
    public function singleAziendaAction() {
        $out = $this->singleAction();
        $out['dir'] = 'azienda/' . $out['dir'] . '/logo';
        return $out;
    }

    /**
     * Inserisce il modulo per caricamento via Drag & Drop delle immagini
     *
     * @Route("/single/strumento", name="drag_drop_single_strumento_row")
     * @Method("post")
     * @Template("EphpDragDropBundle:DragDrop:singleStrumento.html.php")
     */
    public function singleStrumentoAction() {
        $out = $this->singleAction();
        return $out;
    }

    /**
     * Inserisce il modulo per caricamento via Drag & Drop delle immagini
     *
     * @Route("/single/mezzo", name="drag_drop_single_mezzo_row")
     * @Method("post")
     * @Template("EphpDragDropBundle:DragDrop:singleMezzo.html.php")
     */
    public function singleMezzoAction() {
        $out = $this->singleAction();
        return $out;
    }

    /**
     * Inserisce il modulo per caricamento via Drag & Drop delle immagini
     *
     * @Route("/single/allegato", name="drag_drop_single_allegato_row")
     * @Method("post")
     * @Template("EphpDragDropBundle:DragDrop:singleAllegato.html.php")
     */
    public function singleAllegatoAction() {
        $out = $this->multiAction();
        $out['dir'] = 'messenger/' . $out['dir'];
        $out['autoupload'] = $this->getRequest()->get('autoupload');
        $out['id'] = $out['id'] != 'fileupload' ? $out['id'] : 'reply_form';
         $out['css'] = $this->getRequest()->get('nuvola', true) ?
            array(
                'load' => 'box-lightblue text-lightblue text-grey padding-5 rounded-3 gradient-light left no-margin margin-left-5 hover',
                'loadstyle' => '',
                'x' => 'box-lightblue padding-5 gradient-light left rounded-3 no-margin margin-left-10 hover',
                'xstyle' => 'width: 30px; padding: 2px 0; left: -4px;',
            ) :
            array(
                'load' => 'ask-input-grey text-grey text-small rounded-3 gradient-light left no-margin margin-left-5 hover',
                'loadstyle' => 'padding: 3px 6px;',
                'x' => 'box-light gradient-light left rounded-3 no-margin margin-left-5 hover',
                'xstyle' => 'width: 23px; padding: 2px 0; left: -4px;',
            );
        return $out;
    }

    /**
     * Inserisce il modulo per caricamento via Drag & Drop delle immagini
     *
     * @Route("/single/allegato/script", name="drag_drop_single_allegato_row")
     * @Method("post")
     * @Template("EphpDragDropBundle:DragDrop:singleAllegatoScript.html.php")
     */
    public function singleAllegatoScriptAction() {
        $out = $this->multiAction();
        $out['dir'] = 'messenger/' . $out['dir'];
        $out['autoupload'] = $this->getRequest()->get('autoupload');
        $out['id'] = $out['id'] != 'fileupload' ? $out['id'] : 'reply_form';
        $out['css'] = $this->getRequest()->get('nuvola', true) ?
            array(
                'load' => 'box-lightblue text-lightblue text-grey padding-5 rounded-3 gradient-light left no-margin margin-left-5 hover',
                'loadstyle' => '',
                'x' => 'box-lightblue padding-5 gradient-light left rounded-3 no-margin margin-left-10 hover',
                'xstyle' => 'width: 30px; padding: 2px 0; left: -4px;',
            ) :
            array(
                'load' => 'ask-input-grey text-grey text-small rounded-3 gradient-light left no-margin margin-left-5 hover',
                'loadstyle' => 'padding: 3px 6px;',
                'x' => 'box-light gradient-light left rounded-3 no-margin margin-left-5 hover',
                'xstyle' => 'width: 23px; padding: 2px 0; left: -4px;',
            );
        return $out;
    }

    /**
     * Inserisce il modulo per caricamento via Drag & Drop delle immagini
     *
     * @Route("/nuvola/allegato", name="drag_drop_single_nuvola_doc_row")
     * @Method("post")
     * @Template("EphpDragDropBundle:DragDrop:singleAllegatoNuvola.html.php")
     */
    public function singleAllegatoNuvolaAction() {
        $out = $this->multiAction();
        $out['dir'] = $out['env'] ? $out['env'] : 'nuvola/' . $out['dir'];
        $out['autoupload'] = $this->getRequest()->get('autoupload');
        $out['id'] = $out['id'] != 'fileupload' ? $out['id'] : 'reply_form';
        $out['css'] = $this->getRequest()->get('nuvola', true) ?
            array(
                'load' => 'box-lightblue text-lightblue text-grey padding-5 rounded-3 gradient-light left no-margin margin-left-5 hover',
                'loadstyle' => '',
                'x' => 'box-lightblue padding-5 gradient-light left rounded-3 no-margin margin-left-10 hover',
                'xstyle' => 'width: 30px; padding: 2px 0; left: -4px;',
            ) :
            array(
                'load' => 'ask-input-grey text-grey text-small rounded-3 gradient-light left no-margin margin-left-5 hover',
                'loadstyle' => 'padding: 3px 6px;',
                'x' => 'box-light gradient-light left rounded-3 no-margin margin-left-5 hover',
                'xstyle' => 'width: 23px; padding: 2px 0; left: -4px;',
            );
        return $out;
    }
    /**
     * Inserisce il modulo per caricamento via Drag & Drop delle immagini
     *
     * @Route("/market/allegato", name="drag_drop_single_market_doc_row")
     * @Method("post")
     * @Template("EphpDragDropBundle:DragDrop:singleAllegatoNuvola.html.php")
     */
    public function singleAllegatoMarketAction() {
        $out = $this->multiAction();
        $out['dir'] = $out['env'] ? $out['env'] : 'market/' . $out['dir'];
        $out['autoupload'] = $this->getRequest()->get('autoupload');
        $out['id'] = $out['id'] != 'fileupload' ? $out['id'] : 'reply_form';
        $out['css'] = $this->getRequest()->get('nuvola', true) ?
            array(
                'load' => 'box-lightblue text-lightblue text-grey padding-5 rounded-3 gradient-light left no-margin margin-left-5 hover',
                'loadstyle' => '',
                'x' => 'box-lightblue padding-5 gradient-light left rounded-3 no-margin margin-left-10 hover',
                'xstyle' => 'width: 30px; padding: 2px 0; left: -4px;',
            ) :
            array(
                'load' => 'ask-input-grey text-grey text-small rounded-3 gradient-light left no-margin margin-left-5 hover',
                'loadstyle' => 'padding: 3px 6px;',
                'x' => 'box-light gradient-light left rounded-3 no-margin margin-left-5 hover',
                'xstyle' => 'width: 23px; padding: 2px 0; left: -4px;',
            );
        return $out;
    }

    /**
     * Inserisce il modulo per caricamento via Drag & Drop delle immagini
     *
     * @Route("/nuvola/allegato", name="drag_drop_single_nuvola_img_row")
     * @Method("post")
     * @Template("EphpDragDropBundle:DragDrop:singleImmagineNuvola.html.php")
     */
    public function singleImmagineNuvolaAction() {
        $out = $this->multiAction();
        $out['dir'] = $out['env'] ? $out['env'] : 'nuvola/' . $out['dir'];
        $out['autoupload'] = $this->getRequest()->get('autoupload');
        $out['id'] = $out['id'] != 'fileupload' ? $out['id'] : 'reply_form';
        $out['css'] = $this->getRequest()->get('nuvola', true) ?
            array(
                'load' => 'box-lightblue text-lightblue text-grey padding-5 rounded-3 gradient-light left no-margin margin-left-5 hover',
                'loadstyle' => '',
                'x' => 'box-lightblue padding-5 gradient-light left rounded-3 no-margin margin-left-10 hover',
                'xstyle' => 'width: 30px; padding: 2px 0; left: -4px;',
            ) :
            array(
                'load' => 'ask-input-grey text-grey text-small rounded-3 gradient-light left no-margin margin-left-5 hover',
                'loadstyle' => 'padding: 3px 6px;',
                'x' => 'box-light gradient-light left rounded-3 no-margin margin-left-5 hover rem_img',
                'xstyle' => 'width: 23px; padding: 2px 0; left: -4px;',
            );
        return $out;
    }
    
    /**
     * Inserisce il modulo per caricamento via Drag & Drop delle immagini
     *
     * @Route("/market/allegato", name="drag_drop_single_market_img_row")
     * @Method("post")
     * @Template("EphpDragDropBundle:DragDrop:singleImmagineNuvola.html.php")
     */
    public function singleImmagineMarketAction() {
        $out = $this->multiAction();
        $out['dir'] = $out['env'] ? $out['env'] : 'market/' . $out['dir'];
        $out['autoupload'] = $this->getRequest()->get('autoupload');
        $out['id'] = $out['id'] != 'fileupload' ? $out['id'] : 'reply_form';
        $out['css'] = $this->getRequest()->get('nuvola', true) ?
            array(
                'load' => 'box-lightblue text-lightblue text-grey padding-5 rounded-3 gradient-light left no-margin margin-left-5 hover',
                'loadstyle' => '',
                'x' => 'box-lightblue padding-5 gradient-light left rounded-3 no-margin margin-left-10 hover',
                'xstyle' => 'width: 30px; padding: 2px 0; left: -4px;',
            ) :
            array(
                'load' => 'ask-input-grey text-grey text-small rounded-3 gradient-light left no-margin margin-left-5 hover',
                'loadstyle' => 'padding: 3px 6px;',
                'x' => 'box-light gradient-light left rounded-3 no-margin margin-left-5 hover rem_img',
                'xstyle' => 'width: 23px; padding: 2px 0; left: -4px;',
            );
        return $out;
    }

    /**
     * Inserisce il modulo per caricamento via Drag & Drop delle immagini
     *
     * @Route("/nuvola/allegati", name="drag_drop_multi_nuvola_img_row")
     * @Method("post")
     * @Template("EphpDragDropBundle:DragDrop:multiImmagineNuvola.html.php")
     */
    public function multiImmagineNuvolaAction() {
        $out = $this->multiAction();
        $out['dir'] = $out['env'] ? $out['env'] : 'nuvola/' . $out['dir'];
        $out['autoupload'] = $this->getRequest()->get('autoupload');
        $out['id'] = $out['id'] != 'fileupload' ? $out['id'] : 'reply_form';
        $out['more'] = $this->getRequest()->get('more', 2);
        $out['css'] = $this->getRequest()->get('nuvola', true) ?
            array(
                'load' => 'box-lightblue text-lightblue text-grey padding-5 rounded-3 gradient-light left no-margin margin-left-5 hover',
                'loadstyle' => '',
                'x' => 'box-lightblue padding-5 gradient-light left rounded-3 no-margin margin-left-10 hover',
                'xstyle' => 'width: 30px; padding: 2px 0; left: -4px;',
            ) :
            array(
                'load' => 'ask-input-grey text-grey text-small rounded-3 gradient-light left no-margin margin-left-5 hover',
                'loadstyle' => 'padding: 3px 6px;',
                'x' => 'box-light gradient-light left rounded-3 no-margin margin-left-5 hover rem_img',
                'xstyle' => 'width: 23px; padding: 2px 0; left: -4px;',
            );
        return $out;
    }
    /**
     * Inserisce il modulo per caricamento via Drag & Drop delle immagini
     *
     * @Route("/market/allegati", name="drag_drop_multi_market_img_row")
     * @Method("post")
     * @Template("EphpDragDropBundle:DragDrop:multiImmagineNuvola.html.php")
     */
    public function multiImmagineMarketAction() {
        $out = $this->multiAction();
        $out['dir'] = $out['env'] ? $out['env'] : 'market/' . $out['dir'];
        $out['autoupload'] = $this->getRequest()->get('autoupload');
        $out['id'] = $out['id'] != 'fileupload' ? $out['id'] : 'reply_form';
        $out['more'] = $this->getRequest()->get('more', 2);
        $out['css'] = $this->getRequest()->get('nuvola', true) ?
            array(
                'load' => 'box-lightblue text-lightblue text-grey padding-5 rounded-3 gradient-light left no-margin margin-left-5 hover',
                'loadstyle' => '',
                'x' => 'box-lightblue padding-5 gradient-light left rounded-3 no-margin margin-left-10 hover',
                'xstyle' => 'width: 30px; padding: 2px 0; left: -4px;',
            ) :
            array(
                'load' => 'ask-input-grey text-grey text-small rounded-3 gradient-light left no-margin margin-left-5 hover',
                'loadstyle' => 'padding: 3px 6px;',
                'x' => 'box-light gradient-light left rounded-3 no-margin margin-left-5 hover rem_img',
                'xstyle' => 'width: 23px; padding: 2px 0; left: -4px;',
            );
        return $out;
    }

    /**
     * Inserisce il modulo per caricamento via Drag & Drop delle immagini
     *
     * @Route("/multi/allegato", name="drag_drop_multi_allegato_row")
     * @Method("post")
     * @Template("EphpDragDropBundle:DragDrop:multiAllegato.html.php")
     */
    public function multiAllegatoAction() {
        $out = $this->multiAction();
        $out['dir'] = 'messenger/' . $out['dir'];
        $out['autoupload'] = $this->getRequest()->get('autoupload');
        $out['id'] = $out['id'] != 'fileupload' ? $out['id'] : 'reply_form';
        return $out;
    }

    /**
     * Inserisce il modulo per caricamento via Drag & Drop delle immagini
     *
     * @Route("/multi/impianto", name="drag_drop_multi_impianto_row")
     * @Method("post")
     * @Template("EphpDragDropBundle:DragDrop:multiImpianto.html.php")
     */
    public function multiImpiantoAction() {
        $out = $this->multiAction();
        $out['dir'] = 'showroom/' . $out['dir'] . '/impianti';
        $out['autoupload'] = $this->getRequest()->get('autoupload');
        return $out;
    }

    /**
     * Inserisce il modulo per caricamento via Drag & Drop delle immagini
     *
     * @Route("/multi/impianto", name="drag_drop_multi_impianto_row")
     * @Method("post")
     * @Template("EphpDragDropBundle:DragDrop:multiReferenza.html.php")
     */
    public function multiReferenzaAction() {
        $out = $this->multiAction();
        $out['dir'] = 'showroom/' . $out['dir'] . '/referenze';
        $out['autoupload'] = $this->getRequest()->get('autoupload');
        return $out;
    }

    /**
     * Inserisce il modulo per caricamento via Drag & Drop delle immagini
     *
     * @Route("/multi/documento", name="drag_drop_multi_documento_row")
     * @Method("post")
     * @Template("EphpDragDropBundle:DragDrop:multiDocumento.html.php")
     */
    public function multiDocumentoAction() {
        $out = $this->multiAction();
        $out['dir'] = 'showroom/' . $out['dir'] . '/documenti';
        return $out;
    }

    /**
     * Inserisce il modulo per caricamento via Drag & Drop delle immagini
     *
     * @Route("/multi/documento", name="drag_drop_multi_documento_row")
     * @Method("post")
     * @Template("EphpDragDropBundle:DragDrop:multiDocumentoProgetto.html.php")
     */
    public function multiDocumentoProgettoAction() {
        $out = $this->multiAction();
        $out['dir'] = 'progetto/' . $out['dir'];
        return $out;
    }

    /**
     * Inserisce il modulo per caricamento via Drag & Drop delle immagini
     *
     * @Route("/multi/documento", name="drag_drop_multi_documento_row")
     * @Method("post")
     * @Template("EphpDragDropBundle:DragDrop:multiDocumentoProgettoScheda.html.php")
     */
    public function multiDocumentoProgettoSchedaAction() {
        $out = $this->multiAction();
        $out['dir'] = 'progetto/' . $out['dir'];
        return $out;
    }

    /**
     * Inserisce il modulo per caricamento via Drag & Drop delle immagini
     *
     * @Route("/single/copertina", name="drag_drop_single_documento")
     * @Method("post")
     * @Template("EphpDragDropBundle:DragDrop:singleCopertina.html.php")
     */
    public function singleCopertinaAction() {
        $out = $this->singleAction();
        $out['dir'] = 'progetto/' . $out['dir'] . '/copertina';
        return $out;
    }

}
