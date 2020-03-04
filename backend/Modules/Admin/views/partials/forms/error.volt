{% if form.hasMessagesFor(fieldName) %}
	{% set messages = form.getMessagesFor(fieldName) %}
	<ul class="parsley-errors-list filled">
		<li class="parsley-required">{{ messages[0] }}</li>
	</ul>
{% endif %}