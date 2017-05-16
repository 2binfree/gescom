<?php

namespace GescomBundle\Controller;

use GescomBundle\Entity\User;
use GescomBundle\Form\ChangePasswordType;
use GescomBundle\Form\ForgetPasswordType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class SecurityController extends Controller
{

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/login", name="login")
     */
    public function loginAction(Request $request)
    {
        $authUtils = $this->get('security.authentication_utils');
        $error = $authUtils->getLastAuthenticationError();
        $lastUsername = $authUtils->getLastUsername();
        return $this->render('@Gescom/Security/login.html.twig', array(
            'error' => $error,
            'lastUsername' => $lastUsername,
        ));
    }

    /**
     * @param Request $request
     * @Route("/forgotten", name="forgotten")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function forgottenAction(Request $request)
    {
        $user = new User();
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(ForgetPasswordType::class, $user);
        $form->handleRequest($request);
        $message = "";
        if ($form->isSubmitted() && $form->isValid()){
            /** @var User $newUser */
            $newUser = $em->getRepository("GescomBundle:User")->findOneBy(["username" => $user->getUsername()]);
            if (null === $newUser){
                $message = "Nous n'avons pas trouvé cet utilisateur";
            } else {
                $newUser->setPasswordChangeToken($newUser->generateToken());
                $dueDate = new \DateTime("now");
                $dueDate->add(new \DateInterval("P1D"));
                $newUser->setPasswordChangeLimitDate($dueDate);
                $em->persist($newUser);
                $em->flush();
                $email = \Swift_Message::newInstance()
                    ->setSubject('Gescom : réinitilisation du mot de passe')
                    ->setFrom('admin@gescom.com')
                    ->setTo($newUser->getEmail())
                    ->setBody(
                        $this->renderView("@Gescom/mail/forgetpassword.html.twig", [
                            "resetPasswordLink" => $this->generateUrl("reset", [
                                "token" => $newUser->getPasswordChangeToken(),
                            ],
                            UrlGeneratorInterface::ABSOLUTE_URL),
                        ]),
                        'text/html'
                    );
                $this->get('mailer')->send($email);
                $this->addFlash("notice", "Un mail a été envoyé à l'adresse de l'utilisateur.");
                return $this->redirectToRoute('homepage');
            }
        }
        return $this->render("@Gescom/Security/passwordProcess.html.twig", [
            "form" => $form->createView(),
            "userName" => $user->getUsername(),
            "message" => $message,
        ]);
    }

    /**
     * @param Request $request
     * @param string $token
     * @Route("/reset/{token}", name="reset")
     * @return Response
     */
    public function resetPasswordAction(Request $request, $token)
    {
        $message = "";
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository("GescomBundle:User")->findOneBy(["passwordChangeToken" => $token]);
        $today = new \DateTime("now");
        if (null !== $user && $user->getPasswordChangeLimitDate() > $today) {
            $user->setPassword("");
            $form = $this->createForm(ChangePasswordType::class, $user);
            $form->handleRequest($request);
            if ($form->isValid() && $form->isSubmitted()) {
                $password = $user->getPassword();
                $verificationPassword = $request->request->get("gescom_bundle_user_type")["passwordCompare"];
                if ($password === $verificationPassword) {
                    $encoder = $this->get('security.password_encoder');
                    $encoded = $encoder->encodePassword($user, $user->getPassword());
                    $user->setPassword($encoded);
                    $user->setPasswordChangeLimitDate(null);
                    $user->setPasswordChangeToken(null);
                    $em->flush();
                    $this->addFlash("notice", "Votre mot de passe à bien été changé.");
                    return $this->redirectToRoute("homepage");
                } else {
                    $message = "Les mots de passes ne correspondent pas.";
                }
            }
            return $this->render("@Gescom/Security/passwordProcess.html.twig", [
                "form" => $form->createView(),
                "message" => $message,
            ]);
        } else {
            $this->addFlash("notice", "Cette demande de réinitialisation de mot de passe n'est pas valide.");
            return $this->redirectToRoute("homepage");
        }
    }
}
