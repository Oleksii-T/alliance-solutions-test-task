# aliance Solutions test tesk
## _Финансовая отчетность по работе сотрудников_

(CRM, продажи мед. оборудования)

Необходимо создать API финансовой отчетности сотрудников. 
У каждой Компании есть Сотрудники, которые имеют фиксированную ставку и бонусы (проценты) с каждого Клиента. Проценты зависят от того на какую сумму была получена оплата от Клиента.
У Компании есть 20 Сотрудников, 2000 Клиентов, и 1000 оплат в месяц от Клиентов. 


##### Проценты бонусов Сотрудников от суммы оплаты:
- 250-1000 - 5%
- 1000-2000 - 10%
- 2000-5000 - 15%
- 5000 - выше - 20%

##### Нужно сделать отчеты по Компании и по Сотрудникам:

- По скольким Клиентам Компании (и на какую сумму) были получены оплаты за выбранный период;
- По скольким Клиентам (и на какую сумму) каждого сотрудника были получены оплаты за выбранный период;
- Сколько и в какой валюте было получено денег компанией (долл/евро/etc) за период;
- Расчет ЗП по Сотрудникам (фикс.ставка + проценты) помесячно;
- Средняя ЗП Сотрудников за выбранный период;
- Средняя доход от Клиентов по странам;
- Кол-во Клиентов, выполнивших более 1 оплаты по каждому Сотруднику.

##### Необходимо создать:
- сиды
- Postman collection на все эндпоинты
- миграции.

Для выполнение не нужен UI, необходимо создать API по правилам и соглашению REST.