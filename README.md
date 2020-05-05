This php code creates a digitalData variable in any Wordpress instalation. The digitalData dataLayer follows the recommendation of the World Wide Web Consortium for creating a customer experience digital data schema. Those recomendations are written in [this pdf](https://www.w3.org/2013/12/ceddl-201312.pdf). The document is not actual (from 2013) but it is the last (and only) version. Therefore there are some recomendations for user object that I just removed. Those are the ones containing any kind of PII

### Example of output:
```javascript
digitalData ={
   "pageInstanceID":"155-Usando_emojis_en_nuestros_nombres_de_segmento-publish",
   "page":{
      "pageInfo":{
         "pageID":"155",
         "pageName":"Usando emojis en nuestros nombres de segmento",
         "destinationURL":"https://analista-digital.com/ideas-y-consejos/usando-emojis-en-nuestros-nombres-de-segmento/",
         "referringURL":"https://analista-digital.com/",
         "sysEnv":"desktop",
         "variant":"",
         "version":"040520_545",
         "author":"Agustín",
         "creationDate":"2020-04-28",
         "modificationnDate":"2020-05-04",
         "language":"es_ES"
      },
      "category":{
         "categories":"[{"id":13,"name":"Ideas y Consejos","slug":"ideas-y-consejos"}]",
         "primaryCategory":"[{"id":13,"name":"Ideas y Consejos","slug":"ideas-y-consejos"}]",
         "pageType":"post"
      },
      "tag":{
         "tags":"["emojis","segments"]"
      }
   },
   "user":{
      "auth":"logged-in",
      "role":"administrator",
      "hash_id":"b0babad3173de88eb6f6cda4589d98ccb756fb1fe5e5773c39eed6c911e1906d"
   },
   "version":"1.0"
}
```
