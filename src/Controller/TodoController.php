<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class TodoController
 * @package App\Controller
 * @Route("todo")
 */
class TodoController extends AbstractController
{
    /**
     * @Route("/", name="todo")
     */
    public function index(SessionInterface $session): Response
    {
        if(! $session->has('todos')) {
            $todos = [
                'lundi' => 'HTML',
                'mardi' => 'CSs',
                'mercredi' => 'Js',
            ];
            $session->set('todos', $todos);
            $this->addFlash('info', "Bienvenu dans votre plateforme de gestion des todos");
        }
        return $this->render('todo/index.html.twig');
    }

    /**
     * @Route("/add/{name?rien}/{content?rien}", name="addTodo")
     */
    public function addTodo($name, $content, SessionInterface $session) {

        if (($name=='rien') or ($content=='rien') ) {
            //ko => messsage erreur + redirection
            $this->addFlash('error', "Vous n'avez rien ajouté");
        }
        else {
            // Vérifier que ma session contient le tableau de todo
            if (!$session->has('todos')) {
                //ko => messsage erreur + redirection
                $this->addFlash('error', "La liste des todos n'est pas encore initialisée");
            } else {
                //ok
                // Je vérifie si le todo existe
                $todos = $session->get('todos');
                if (isset($todos[$name])) {
                    //ko => messsage erreur + redirection
                    $this->addFlash('error', "Le todo $name existe déjà");
                } else {
                    //ok => j ajoute et je redirige avec message succès
                    $todos[$name] = $content;
                    $session->set('todos', $todos);
                    $this->addFlash('success', "Le todo $name a été ajouté avec succès");
                }

            }
        }
        return $this->redirectToRoute('todo');
    }
    /**
     * @Route("/delete/{name?rien}", name="deleteTodo")
     */
    public function deleteTodo($name, SessionInterface $session) {
            if (($name=='rien')) {
                //ko => messsage erreur + redirection
                $this->addFlash('error', "Vous n'avez rien supprimé");
            }
else {
    // Vérifier que ma session contient le tableau de todo
    if (!$session->has('todos')) {
        //ko => messsage erreur + redirection
        $this->addFlash('error', "La liste des todos n'est pas encore initialisée");
    } else {
        //ok
        // Je vérifie si le todo existe
        $todos = $session->get('todos');
        if (!(isset($todos[$name]))) {
            //ko => messsage erreur + redirection
            $this->addFlash('error', "Le todo $name n'existe pas");
        } else {
            //ok => je supprime et je redirige avec message succès
            unset($todos[$name]);
            $session->set('todos', $todos);
            $this->addFlash('success', "Le todo $name a été supprimé avec succès");
        }
    }
}
        return $this->redirectToRoute('todo');
    }
    /**
     * @Route("/edit/{name?rien}/{content?rien}", name="editTodo")
     */
    public function editTodo($name,$content, SessionInterface $session) {
        if (($name=='rien') or ($content=='rien') ) {
            //ko => messsage erreur + redirection
            $this->addFlash('error', "Vous n'avez rien modifié");
        }
        else {

        // Vérifier que ma session contient le tableau de todo
        if (!$session->has('todos')) {
            //ko => messsage erreur + redirection
            $this->addFlash('error', "La liste des todos n'est pas encore initialisée");
        } else {
            //ok
            // Je vérifie si le todo existe
            $todos = $session->get('todos');
            if (!(isset($todos[$name]))) {
                //ko => messsage erreur + redirection
                $this->addFlash('error', "Le todo $name n'existe pas");
            } else {
                //ok => je modifie et je redirige avec message succès
                $todos[$name] = $content;
                $session->set('todos', $todos);
                $this->addFlash('success', "Le todo $name a été modifié avec succès");
            }
        }   }
        return $this->redirectToRoute('todo');
    }
}
