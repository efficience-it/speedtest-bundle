# Speedtest bundle

This speedtest is a Symfony-based bundle. It's an overhaul of the [LibreSpeed](https://librespeed.org/) project.

It was created by Efficience IT, a French company located in Lille.

## Installation

### Step 1: Install the bundle with Composer

Require the `efficience-it/speedtest-bundle` with [Composer](https://getcomposer.org/).

```bash
$ composer require efficience-it/speedtest-bundle
``` 

### Step 2: Configure the speedtest in your project

First, verify if the line below is in the `bundles.php` file. If not, copy and paste it.

```php
EfficienceIt\SpeedtestBundle\SpeedtestBundle::class => ['all' => true]
```

Second, create the `speedtest.yaml` file in the `config/routes` folder. 

Copy the code below and paste it in this file.

```yaml
speedtest:
    resource: '@SpeedtestBundle/Resources/config/routes.yaml'
    prefix: /speedtest
```

### Step 3: Add the speedtest on your website

On any controller, you can call the SpeedtestService and its `displaySpeedtest` function.

Here is an example of a controller, with a route tht includes the bundle:

```php
class HomeController extends AbstractController
{
    /**
     * @Route("/home", name="app_home")
     */
    public function index(SpeedtestService $speedtestService): Response
    {
        // Replace 'home/index.html.twig' with the name of your template
        return $this->render('home/index.html.twig', [
            'speedtest' => $speedtestService->displaySpeedtest()
        ]);
    }
}
```

To display the speedtest on your page, just include it in your template file as below:

```php
{% extends 'base.html.twig' %}

{% block title %}Hello HomeController!{% endblock %}

{% block body %}
    {% include speedtest %}
{% endblock %}
```

You can access to your route (in this example `localhost/home`), and the speedtest should appear !

## How to retrieve the results ?

Create a new Controller (for example `ResultsController`), and copy/paste this code:

```php
/* DON'T ADD A @Route ANNOTATION */
class ResultsController extends AbstractController
{
    /* DON'T CHANGE THIS ROUTE ! */
    /**
     * @Route("/speedtest-results", name="speedtest_results", methods={"POST"})
     */
    public function speedtestResults(Request $request): Response
    {
        if (!$request->isXmlHttpRequest()) {
            throw new AccessDeniedException();
        }
        $requestContent = json_decode($request->getContent(), true);
        dump($requestContent);

        return new JsonResponse($requestContent);
    }
}
```

With this route (called in AJAX), you can retrieve your speedtest results and do whatever you want with it !
