## on request
if URL doesn't exist in db
- or store SHA512 of HTML content to check for uniqueness in content instead
  - potential issue of conditional script changing the SHA
- insert script tag
- query all classes, IDs, tags
- foreach occurence, save to DB via ajax
    - query type (class, ID, tag)
    - name
    - position

## crawl wp-sitemap.xml (wp 5.5)
- crawl all pages and extract classes, tags and IDs with php/regex
