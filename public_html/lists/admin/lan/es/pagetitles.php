<?php
switch ($page) {
  case "home": $page_title = 'Página Principal de Administrador';break;
  case "attributes": $page_title = 'Configurar Atributos';break;
	case "stresstest": $page_title = 'Stress Test';break;
  case "list": $page_title = "Listado de Listas";break;
  case "editattributes": $page_title = "Configurar Atributos";break;
  case "editlist": $page_title = "Editar una Lista";break;
  case "import4": $page_title = "Importar emails de una base de datos PHPList remota";break;
  case "import3": $page_title = "Importar emails de IMAP";break;
  case "import2":
  case "import1":
  case "import": $page_title = "Importar emails";break;
  case "export": $page_title = "Exportar usuarios";break;
  case "initialise": $page_title = "Inicializar la base de datos";break;
	case "send": $page_title = "Enviar un Mensaje";break;
	case "preparesend": $page_title = "Preparar un mensaje para su envío";break;
	case "sendprepared": $page_title = "Enviar un mensaje preparado";break;
	case "members": $page_title = "Lista de Miembros";break;
  case "users": $page_title = "Lista de Todos los Usuarios";break;
  case "reconcileusers": $page_title = "Conciliar usuarios";break;
  case "user": $page_title = "Detalles de un usuario";break;
  case "messages": $page_title = "Todos los mensajes";break;
  case "message": $page_title = "Ver un mensaje";break;
  case "processqueue": $page_title = "Enviar listado de mensajes en espera";break;
  case "defaults": $page_title = "Atributos de utilidad por defecto ";break;
  case "upgrade": $page_title = "Actualizar PHPlist";break;
  case "templates": $page_title = "Plantillas en el sistema";break;
  case "template": $page_title = "Añadir o Editar plantillas";break;
  case "viewtemplate": $page_title = "Vista Previa de Plantilla";break;
  case "configure": $page_title = "Configurar PHPlist";break;
  case "admin": $page_title = "Editar una cuenta de Administrador";break;
  case "admins": $page_title = "Listado de Administradores";break;
  case "adminattributes": $page_title = "Configurar Atributos de administrador";break;
  case "processbounces": $page_title = "Recuperar mensajes rebotados del servidor";break;
  case "bounces": $page_title = "Lista de mensajes rebotados";break;
  case "spageedit": $page_title = "Editar página de suscripción";break;
  case "spage": $page_title = "Páginas de Suscripción";break;
  case "eventlog": $page_title = "Registro de eventos";break;
  case "getrss": $page_title = "Recuperar RSS feeds";break;
  case "viewrss": $page_title = "Ver Items RSS";break;
  case "community": $page_title = "Bienvenido a la comunidad de PHPlist";break;
  case "vote": $page_title = "Vote por PHPlist";break;
  case "login": $page_title = "Login";break;
  case "logout": $page_title = "Log Out";break;
}
?>
