<div class="modal fade" id="form-token" tabindex="-1" role="dialog">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
    		<div class="modal-body">
				<h5 class="title-listing">Отправка сообщения с возможностью восстановления корзины</h5>
				<div class="info">
					<p>Форма позволяет отправить клиенту сообщение на почту, также может быть использовано для восстановления потерянной корзины, полезно при телефонном об звоне клиентов.</p>
					<p>Чтобы отправить ссылку на восстановления корзины используйте подготовленную переменную. <b>{TOKEN_URL}</b> - переменная создаст ссылку для едино разового восстановления, если клиент зарегистрированный произойдет авторизация.</p>
					<p><b>Важно:</b> после перехода по ссылке у покупателя создается новая метка в браузере, если вы отправите сообщение себе для проверки и перейдете по ссылке - клиент потеряет брошенную корзину, и она закрепится за вами в вашем браузере.</p>
				</div>				
				<form class="form-token" action="<?= $mesage_action; ?>">
					<div class="form-group" style="padding-top: 0">
						<label>Почта получателя</label>
						<input class="form-control" type="text" name="email">
					</div>
					<div class="form-group">
						<label>Тема письма</label>
						<input class="form-control" type="text" name="subject">
					</div>
					<div class="form-group">
						<label>Сообщение</label>
						<textarea class="form-control" name="mesage"></textarea>
					</div>
					<input type="hidden" name="cart_id">
					<input type="hidden" name="store_id">
					<div class="text-right">
						<button type="button" class="btn btn-sm btn-default" data-dismiss="modal">Закрыть</button>
						<button class="btn btn-sm btn-primary send" type="button" data-loading-text="<i class='fa fa-spinner'></i>">Отправить</button>
					</div>
				</form>				
      		</div>
    	</div>
  	</div>
</div>

