Prueba Técnica Bodas
=====

Es un proyect en Symfony 2.7.

Primero de todo comentar algunos aspectos de la prueba técnica:

### Server

- En el 10% de las peticiones se devolverá un error y el simulador deberá reintentar la petición.

En este punto las peticiones que reintenta el simulador no son las mismas que 
las intentadas por primera vez.
 
- En la petición satisfactoria no 60 indica al simulador que ha finalizado la operación y le devuelve los
resultados.

Este punto se ha cambiado la funcionalidad ya que el simulador, en cada respuesta que recibe, actualiza los
datos de la gráfica. La primera aproximación fue que únicamente recibiera los resultados en la última petición.

- Un sistema de contador en memcache/redis/.. con el número de peticiones, exitosas y fallidas, recibidas en
el día en curso.

Este punto he creado dos variables en redis (count:day:success y count:day:fail) para contabilizar cada una de las 
peticiones por separado.

### Cliente

- Mostrar la suma del recorrido y el punto cardinal más frecuente.

La muestra del recorrida es la suma de todos ellos. A la hora de entregarlo he pensado quedebería ser
la diferencia si va, por ejemplo, primero a norte y luego a sud o este y después a oeste.

- Actualizar la gráfica cada 2 o 3 segundos mientras el simulador está en ejecución.

La actualización de la gráfica se realiza cada vez que el servidor responde una de nuestras peticiones
ya que envía los datos.

### Preguntas

- El administrador de sistemas comenta que se están realizando demasiadas inserciones, 
bloqueando la base de datos. ¿Qué propondrías para mejorar el rendimiento?

Particionar tablas con gran cantidad de datos.

Recopilar un conjunto de operaciones (inserts, updates y deletes) y enviarlas en una única transacción.

RabbitMQ: si las inserciones no son prioritarias, se pueden mandar a unas colas de RabbitMQ y que las ejecute cuando 
la base de datos no esté bloqueada. 

- Las consultas son muy lentas. ¿Qué solución le darías?

Cachear con Redis (o similares) las peticiones que no cambian muy a menudo. 

Crear índices

Cambiar los OR por IN, cuando tenemos más de un valor para comparar.

Minimizar el coste de los JOIN ya que es la operación más costosa de las bases de datos relaciones.

Especificar siempre los nombres de las columnas en las SELECT