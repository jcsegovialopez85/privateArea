
<h1>{{page}}</h1>
<div>
	{% if session.get('auth') !== null %} 
		{% for username in session.get("auth") %}
			<p>Hello {{username}}</p>
		{% endfor %}	
		{{ partial("layouts/logout") }}
	{% else %}
		not session
	{% endif %}
</div>
