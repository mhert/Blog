#mhert/blog

To download google font use `https://google-webfonts-helper.herokuapp.com/fonts/raleway?subsets=latin`

```
eb create blog-dev
```

```
eb create blog-prod
```

```
aws elasticbeanstalk update-environment --environment-name "blog-dev" --option-settings file://eb-environment-blog-dev.json
```

```
aws elasticbeanstalk update-environment --environment-name "blog-prod" --option-settings file://eb-environment-blog-prod.json
```

```
eb deploy "blog-dev"
```

```
eb deploy "blog-prod"
```