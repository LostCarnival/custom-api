<?php

namespace Drupal\tickets_api\Plugin\rest\resource;

use Drupal\Core\Session\AccountProxyInterface;
use Drupal\rest\Plugin\ResourceBase;
use Drupal\rest\ResourceResponse;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;



/**
 * Provides a resource to create new ticket.
 *
 * @RestResource(
 *   id = "create_ticket_resource",
 *   label = @Translation("Create ticket resource"),
 *   uri_paths = {
 *     "create" = "/tickets_api/create-ticket",
 *   }
 * )
 */
class CreateTicketResource extends ResourceBase {

  /**
   * A current user instance.
   *
   * @var \Drupal\Core\Session\AccountProxyInterface
   */
  protected $currentUser;

  /**
   * Constructs a new CreateTicketResource object.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param array $serializer_formats
   *   The available serialization formats.
   * @param \Psr\Log\LoggerInterface $logger
   *   A logger instance.
   * @param \Drupal\Core\Session\AccountProxyInterface $current_user
   *   A current user instance.
   */
  public function __construct(
    array $configuration,
    $plugin_id,
    $plugin_definition,
    array $serializer_formats,
    LoggerInterface $logger,
    AccountProxyInterface $current_user) {
        parent::__construct($configuration, $plugin_id, $plugin_definition, $serializer_formats, $logger);
        $this->currentUser = $current_user;
    }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->getParameter('serializer.formats'),
      $container->get('logger.factory')->get('tickets_api'),
      $container->get('current_user')
    );
  }

  /**
   * Responds to POST requests.
   */
  public function post($entity_data) {

    if (!$this->currentUser->hasPermission('create ticket content')) {
      throw new AccessDeniedHttpException();
    }

    try {
      $entity_data['type'] = 'ticket';



      // Get custom_api user id ............. prototype
      $get_user = \Drupal::entityTypeManager()
        ->getStorage('user')
        ->loadByProperties(['name' => $entity_data['uid']]);
      $get_user_now = reset($get_user);
      if ($get_user_now) {
        $entity_data['uid'] = $get_user_now->id();
      } else {
        $entity_data['uid'] = 1;
      }



      $ticket = \Drupal::entityTypeManager()
        ->getStorage('node')
        ->create($entity_data);
      $ticket->save();
      return new ResourceResponse($ticket);
    } catch (\Exception $e) {
      return new ResourceResponse('Что-то пошло не так в момент создания заявки. Проверьте формат передачи данных!', 400);
    }

  }

}