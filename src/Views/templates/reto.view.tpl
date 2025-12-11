<h1>Reto</h1>
<div>Nombre: {{nombre}}</div>
<div>Cuenta: {{cuenta}}</div>
<div>Correo: {{correo}}</div>
<div>
    <ul>
    {{foreach colores}}
        <li>{{this}}</li>
    {{endfor colores}}
    </ul>
</div>
