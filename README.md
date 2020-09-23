# Expense App en PHP

![alt text](docs/expense-app-cover.jpg)

Este es el ejercicio final del tutorial para crear una aplicación web completa con PHP. Algunas de las funcionalidades que tiene esta aplicación web son:
- Patrón de diseño Model-View-Controller
- Consultas con MySQL
- Autenticación y registro de usuarios
- Autorización por roles
- Integración de gráficas
- Uso de sesiones

## Instalación del proyecto

Lo primero es clonar el proyecto a tu equipo local

```git clone https://github.com/marcosrivasr/expense-app.git```

## Importar base de datos

Ahora tenemos que crear el esquema de la base de datos.

1. Vamos a la carpeta de db

    ```cd expense-app/db```

2. Vamos a loguearnos a nuestra consola de MySQL (sustituye username con el nombre de usuario de tu conexión)

    ```terminal 
    mysql -u username -p
    ```

3. Creamos una nueva base de datos llamada **expenseapp**

    ```shell
    CREATE DATABASE expenseapp;
    ```

4. Salimos con `exit;` y ahora importamos el archivo `expense-app.sql`

    ```shell    
    mysql -u username -p expenseapp < expense-app.sql;
    ```

## Configuración de proyecto

Para actualizar las conexiones a la base de datos es importante cambiar los datos que se encuentran en `/config/config.php`

Adicional en `public/js/dashboard.js` es necesario verificar que las URLs usadas para hacer solicitudes asíncronas estén también apuntando correctamente de acuerdo a tu servidor.

```
async function getContent(){
  const html = await fetch('http://localhost:8080/expense-app/expenses/create').then(res => res.text());
  return html;
}
```

```
 async function drawChart() {
        const http = await fetch('http://localhost:8080/expense-app/expenses/getExpensesJSON')
        .then(json => json.json())
        .then(res => res);
```