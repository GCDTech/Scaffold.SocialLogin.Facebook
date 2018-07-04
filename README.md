Module.Leaf.TwigView
---
Integration of Twig HTML template engine in a Rhubarb View. 

Creating a Twig View
--- 
 
To create a twig view simply create a View that extends the TwigView class. 

```php
class ProductDetailsView extends TwigView
```

You will be have to implement two methods:

**getTwigFileLocation()** should return the location of your .twig or .html file as a string.
 

```php
function getTwigFileLocation(): string
{
    return __DIR__ . '/File.twig';
}
```

**getTwigVariables()** returns an array of values you want to be available in your Twig Template. 

```php
function getTwigVariables() {
    return [
        'PageTitle' => 'Edit Page', 
        'Type' => $this->model->restModel->Type,
        'NameInput' => $this->leaves['NameTextbox'],
    ];
}
``` 

You will also need an actual Twig File to be compiled. Twig can compile any HTML file, but has additional support for twig features such as controls and variables. To format twig inputs use the PHPStorm .twig file.

TwigView uses the printViewContent method to render the HTML so if you extend printViewContent ensure to include a parent call. 
 
Using Twig
---  
 
Twig uses brackets `{  }` to insert variables and controls into a HTML template.    
 
A variable can be output directly using double brackets.  
```twig 
<p>{{ date }}</p> 
``` 
 
Objects can be access with dot notation in the same way: 
```twig 
<p>Dear {{ recipient.Name }},</p> 
``` 
 
 
Controls can be added using `{%  %}`. 
 
```twig 
{% for purchase in purchases  %} 
    {% if purchase.cancelled %} 
        <p class="red">You cancelled your purchase of a {{purchase.product}} on {{purchase.date}} 
    {% else %} 
        <p> you purchased a {{purchase.product}} on {{purchase.date}} for {{purchase.price}}</p> 
    {% endif %} 
{% endfor %} 
``` 
 
Full Twig Documentation: 
https://twig.symfony.com/doc/2.x/ 