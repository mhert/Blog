#mhert/blog

To download google font use https://google-webfonts-helper.herokuapp.com/fonts/raleway?subsets=latin

CREATE TABLE posts(
                        id UUID PRIMARY KEY NOT NULL,
                        created TIMESTAMP NOT NULL,
                        content TEXT NOT NULL
)