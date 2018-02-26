<h1>Home</h1>		
{% if session.get('auth') !== null  %}
<h2>Pages</h2>
<ul>
    <li><a href="/page1">PAGE 1</a></li>
    <li><a href="/page2">PAGE 2</a></li>
    <li><a href="/page3">PAGE 3</a></li>
</ul>
{{ partial("layouts/logout") }}
{% else %}
<p>Go to <a href="/index/login">Login</a> in order to get acces to private area</p>
{% endif %}
