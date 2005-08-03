
<h1>Comunidade PHPlist</h1>
<p><b>&Uacuteltima vers&atildeo</b><br/>
Por favor, certifique-se que voc&ecirc est&aacute utilizando a &uacuteltima vers&atildeo antes de enviar um relat&oacuterio de erro.<br/>
<?php
ini_set("user_agent",NAME. " (PHPlist version ".VERSION.")");
ini_set("default_socket_timeout",5);
if ($fp = @fopen ("http://www.phplist.com/files/LATESTVERSION","r")) {
  $latestversion = fgets ($fp);
  $latestversion = preg_replace("/[^\.\d]/","",$latestversion);
  $v = VERSION;
  $v = str_replace("-dev","",$v);
  if ($v >= $latestversion) {
    print "<font color=green size=2>Parab&eacutens, voc&ecirc est&aacute utilizando a &uacuteltima vers&atildeo</font>";
  } else {
    print "<font color=green size=2>voc&ecirc n&atildeo est&aacute utilizando a &uacuteltima vers&atildeo</font>";
    print "<br/>Sua vers&atildeo: <b>".$v."</b>";
    print "<br/>&Uacuteltima vers&atildeo: <b>".$latestversion."</b>  ";
    print '<a href="http://www.phplist.com/files/changelog">Veja o que mudou</a>&nbsp;&nbsp;';
    print '<a href="http://www.phplist.com/files/phplist-'.$latestversion.'.tgz">Baixar</a>';
  }
} else {
  print "<br/>Veja se h&aacute uma nova vers&atildeo: <a href=http://www.phplist.com/files>clicando aqui</a>";
}
?>
<p>O PHPlist come&ccedilou em 2000 como uma pequena aplica&ccedil&atildeo para o
<a href="http://www.nationaltheatre.org.uk" target="_blank">Teatro Nacional [<i>National Theatre</i>]</a>. Com o passar do tempo cresceu e se tornou um f&aacutecil sistema de Gerenciamento de Rela&ccedilo com o Cliente e o n&uacutemero de sites usando o aplicativo aumentou rapidamente. Mesmo o c&oacutedigo base &eacute fundamentalmente mantido por uma pessoa, ele tem se tornado muito complexo e para garantir a sua qualidade ser&aacute necess&aacuterio integrar outras v&aacuterias pessoas.</p>

<p>Para evitar que se lote as caixas de mensagens dos desenvolvedores, pedimos que n&atildeo encaminhem suas d&uacutevidas diretamente para  <a href="http://tincan.co.uk" target="_blank">Tincan</a>, ao inv&eacutes disso usem outros m&eacutetodos de comunica&ccedil&atildeo dispon&iacuteveis. Isso n&atildeo somente liberar&aacute tempo para a continuidade de desenvolvimento, mas tamb&eacutem criar&aacute um hist&oacuterico de quest&otildees, que poder&aacute ser usado por novos usu&aacuterios para aprender como funciona o sistema.
</a>.</p>
<p>Para facilitar o trabalho da comunidade PHPlist existem diversas op&ccedil&otildees dispon&iacuteis:
<ul>
<li><a href="http://www.phplist.com/forums/" target="_blank">F&oacuteruns</a></li>
<li><a href="#bugtrack">Rastreador de Erros</a></li>
</ul>
</p><hr>
<h1>O que voc&ecirc pode fazer para ajudar</h1>
<p>Se voc&ecirc &eacute um <b>usu&aacuterio comum do PHPlist</b> e acha que j&aacute o conhece bem, voc&ecirc pode ajudar respondendo &agraves quest&otildees dos outros usu&aacuterios.</p>
<p>Se voc&ecirce &eacute <b>novo no PHPlist</b> e tem tido dificuldades em configur&aacute-lo para funcionar em seu site, voc&ecirc pode ajudar tentando procurar a solu&ccedil&atildeo atrav&eacutes das op&ccedil&otildees acima, depois postando imediatamente a mensagem "n&atildeo funciona". Geralmente os problemas que voc&ecirc possa ter est&atildeo relacionados ao ambiente em que a sua  insta&ccedil&atildeo do PHPlist est&aacute rodando. 
Ter somente um desenvolvedor para o PHPlist tem a desvantagem de n&atildeo poder ser testado em todas as plataformas e em todas as vers&otildees do PHP.</p>
<h1>Outras coisas que voc&ecirc pode fazer para ajudar</h1>
<ul>
<li><p>Se voc&ecirc acha que a PHPlist &eacute de grande ajuda, por qu&ecirc n&atildeo fazer com que os outros saibam de sua exist&ecircncia? Provavelmente voc&ecirc teve que correr atr&aacutes para encontr&aacute-lo e decidir us&aacute-lo, depois de ter comparado com outras aplica&ccedil&otildees similares, ent&atildeo voc&ecirc poderia ajudar outras pessoas com a sua experi&ecircncia.</p>

<p>Para faz&ecirc-lo, voc&ecirc pode <?php echo PageLink2 ("vote","Votar")?> no PHPlist, ou escrever a sua opini&atildeo em sites de aplicativos. Voc&ecirc tamb&eacutem pode contar a outras pessoas que voc&ecirc conhece o programa.
</li>
<li><p>Voc&ecirc pode fazer a <b>Tradu&ccedil&atildeo</b> do PHPlist no seu idioma e nos envi&aacute-la. Espero melhorar a internacionaliza&ccedil&tildeo, mas neste momento, voc&e pode simplesmente traduzir o arquivo <i>english.inc</i>.</p>
</li>
<li>
<p>Voc&ecirc pode <b>Testar</b> todas as diferentes caracter&iacutesticas do PHPlist e verificar se elas est&atildeo funcionando corretamente.
Por favor, publique suas opini&otildees nos <a href="http://www.phplist.com/forums/" target="_blank">F&oacuteruns</a>.</p></li>
<li>
<p>Voc&ecirc pode usar o PHPlist para seus clientes pagos (se voc&ecirc &eacute faz parte de uma equipe de web, por exemplo) e mostre que o sistema &eacute uma grande ferramente para atingir os seus objetivos. Ent&atildeo, se eles quiserem algumas mudan&ccedilas, voc&ecirc pode <b>encomendar novas caracter&iacutesticas</b> que s&aeo pagas pelos seus clientes. Se voc&ecirc quiser saber quanto custaria para incluir novas caracter&iacutesticas &agraveo PHPlist, <a href="mailto:phplist@tincan.co.uk?subject=request for quote to change PHPlist">entre em contato</a>.
A maioria das novas caracter&iacutesticas do PHPlist s&atildeo adicionadas a pedidos de clientes pagos. Voc&ecirc ser&aacute beneficiado pagando uma pequena quantia para alcan&ccedilar seus objetivos, e tamb&eacutem beneficiar&aacute a comunidade que ganhar&aacute com as novas caracter&iacutesticas e ainda contribuir&aacute com os desenvolvedores para ganhar algum dinheiro trabalhando no PHPlist :-)</p></li>
<li><p>Se voc&ecirc usa o PHPlist regularmente e tem uma <b>grande quantidade de inscritos</b> (mais de 1000), n&oacutes estamos interessados nas caracter&isticas do seus sistema e no envio de estat&iacutesticas. Como op&ccedil&atildeo padr&atildeo o PHPlist enviar&aacute estatist&iacutesticas para <a href="mailto:phplist-stats@tincan.co.uk">phplist-stats@tincan.co.uk</a>, mas n&atildeo enviar&aacute detalhes do seu sistema. Se voc&ecirc quer contribuir para que a aplica&ccedil&atildeo funcione melhor, seria &oacutetimo que voc&ecirc pudesse nos enviar as informa&ccedil&otildees sobre seu sistema, assim como deixar ativada a op&ccedil&atildeo para enviar as estat&iacutesticas ao endere&ccedilo acima.
As suas informa&ccedil&otildees n&atildeo estar&atildeo dispon&iacuteveis para as pessoas, mas n&oacutes as analisaremos para ter uma id&eacuteia se o PHPlist tem funcionado bem.</p></li>
</ul>

<hr>
<p><b><a name="lists"></a>Lista de Discuss&atildeo</b><br/>
O PHPlist costumava ter uma lista de discus&atildeo, mas foi encerrada. Voc&ecirc ainda pode ler os arquivos da lista. Para suporte ao PHPlist tente os <a href="#forums">f&oacuteruns</a>.
<li>Para acessar os arquivos da lista de discuss&atildeo <a href="http://lists.cupboard.org/archive/tincan.co.uk" target="_blank">clique aqui</a>
</ul>
</p>
<p><b><a name="bugtrack"></a>Rastreador de Erro</b><br/>
Para enviar um relat&oacuterio de erro, acesse <a href="http://mantis.tincan.co.uk/" target="_blank">http://mantis.tincan.co.uk</a>
e crie a sua conta. Voc&ecirc receber&aacute uma senha por email.<br/>
Voc&ecirc pode entrar no sistema "mantis" e enviar o seu relat&oacuteio de erro.</p>
<p>Os detalhes do seu sistema s&atildeo:</p>
<ul>
<li>Vers&atildeo do PHPlist: <?php echo VERSION?></li>
<li>Vers&atildeo do PHP: <?php echo phpversion()?></li>
<li>Servido Web: <?php echo getenv("SERVER_SOFTWARE")?></li>
<li>Site: <a href="http://<?php echo getConfig("website")."$pageroot"?>"><?=getConfig("website")."$pageroot"?></a></li>
<li>Informa&ccedil&otildees do Mysql: <?php echo mysql_get_server_info();?></li>
<li>M&oacutedulos PHP:<br/><ul>
<?php
$le = get_loaded_extensions();
foreach($le as $module) {
    print "<LI>$module\n";
}
?>
</ul></li>
</ul>
<p>Voc&ecirc pode tamb&eacutem usar este sistema para pedir novas caracter&isticas.</p>
<p>Por favor, preste aten&ccedil&atildeo, os email que n&atildeo forem enviados para este sistema ou para os f&oacuteruns ser&atildeo ignorados.</p>
