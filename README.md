# Cidadão-de-Olho

Este projeto contempla o desenvolvimento de uma API para consulta e processamento dos dados abertos da Assembléia Legistlativa
de Minas Gerais, a documentaço da API abertos almg pode ser conferida abaixo:

http://dadosabertos.almg.gov.br/ws/ajuda/sobre

Por meio desta documentaço, foi implementado um Top 5 mensal que mostra os deputados que mais entraram com pedidos de verbas
indenizatórias junto ao órgão, ressalto que aqui recuperamos os valores em reais destas verbas, porém para este ranking
considera-se o quantitativo de verbas, e no o valor total.

## Tecnologias utilizadas

Esta API foi desenvolvida utilizando-se tecnologias difundidas e conhecidas no mercado, e que julgo  ser aptas a desenvolver 
o que foi proposto com bom desempenho (dado a limitaço de uma requisição por segundo, imposta pela WS Dados Abertos).
Segue uma lista contendo todos os softwares necessrios para o funcionamento da ferramenta:

	* Ubuntu 18.04.2 LTS (https://ubuntu.com/)
	* Laravel Framework 5.8.24 (https://laravel.com/docs/5.8)
	* MySQL Server 5.7.26 (https://dev.mysql.com/doc/)
	* PHP >= 7.3
	
## Instalando o software

Para instalar esta ferramenta, basta clonar este repositório em algum diretório de seu desejo
```
git clone https://github.com/joseluiz98/Cidad-o-de-Olho.git
```
Em seguida, será necessário instalar o composer (caso não possua o composer na sua máquina, siga [este](https://www.digitalocean.com/community/tutorials/como-instalar-e-usar-o-composer-no-ubuntu-18-04-pt) tutorial
e usar os seguintes comandos, estando num terminal no diretório onde clonou o repositório:

```
cd cidadao-de-olho
composer install
```
Crie também o banco de dados ___cidadao_de_olho___ que será usado pela aplicaço, (___atente-se para a grafia correta do nome!___)

Após isto, você já pode rodar o servidor, e brincar!
```
sudo php artisan serve --port 8000
```

## Populando o banco e recuperando o Top 5

__Para as fases a seguir, recomendamos (e faremos) a utilização de uma aplicação própria para testes de APIs, como o [Postman](https://www.getpostman.com/downloads/).__

Após [rodar o servidor](https://github.com/joseluiz98/Cidad-o-de-Olho/new/master?readme=1#instalando-o-software),
temos de popular o banco. Para isto, acesse a seguinte url à partir do Postman:

```
http://localhost:8000/api/deputados/initialize
```

Assim, populamos a tabela __Deputados__ à partir dos dados da WS Dados Abertos, agora iremos descobrir quais deputados
pediram mais verbas por mês, considerando apenas o ano de 2017 e o quantitativo de pedidos de cada tipo (Fretaemtn e locação
de veículos, combustvel e óleo lubrificante, etc)
___OBS: este aqui demorará alguns minutos, devido ao limite de requisiçes por segundo imposta pela API externa):___

Para isto. basta acessar:
```
http://localhost:8000/api/deputados/que_mais_pediram_reembolso
```

Verá, após a requisição recuperar e classificar os dados do WebService externo, um retorno do tipo JSON contendo os dados
desejados!
