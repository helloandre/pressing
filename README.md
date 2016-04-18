# Pressing

A Static Site Generator library for PHP.

## Install

```
composer install helloandre/pressing
```

## Usage

```
$config = [];
$pressing = new Pressing\Pressing;
$pressing->generate($config);
```

## Config

#### Pressing Config

`output_dir` - _default: public/_ -  where the generated output will go.

`input_dir` - _default: src/_ - where Pressing should look for files to move to `output_dir`

`template_engine` - _default: Twig_ - what engine to use to render templates 

`template_dir` - _default: templates/_ - where templates are located

#### Frontmatter

Each file may contain a frontmatter config that is JSON inside a top and bottom "marker" of three dashes as the very first thing in the file.

A file will be run through the template engine **ONLY IF** a frontmatter is found.

```
---
{ ... }
---
...
```

or empty

```
---
---
...
```

Any data contained within the frontmatter will be passed to the template.

#### Special Frontmatter

`template` - _default: none_ - declares which template to use.


## Template Engines

currently available template engines:

 - [Twig](twig.sensiolabs.org)