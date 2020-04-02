CREATE TABLE post (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    name VARCHAR (255) NOT NULL,
    slug VARCHAR (255) NOT NULL,
    content TEXT (65000) NOT NULL,
    created_at DATETIME NOT NULL,
    PRIMARY KEY (id)
);
INSERT INTO post (id, name, slug, content, created_at) VALUES (NULL, 'Article de test', 'article-test', 'Lorem Ipsum is the single greatest threat. We are not - we are not keeping up with other websites. Lorem Ipsum best not make any more threats to your website. It will be met with fire and fury like the world has never seen. Does everybody know that pig named Lorem Ipsum? An ‘extremely credible source’ has called my office and told me that Barack Obama’s placeholder text is a fraud.', '2020-04-02 14:00:00');

CREATE TABLE category (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    name VARCHAR (255) NOT NULL,
    slug VARCHAR (255) NOT NULL,
    PRIMARY KEY (id)
);
INSERT INTO `category` (`id`, `name`, `slug`) VALUES (NULL, 'Catégorie #1', 'categorie-1');
INSERT INTO `category` (`id`, `name`, `slug`) VALUES (NULL, 'Catégorie #2', 'categorie-2');

CREATE TABLE post_category (
    post_id INT UNSIGNED NOT NULL,
    category_id INT UNSIGNED NOT NULL,
    PRIMARY KEY (post_id, category_id),
    CONSTRAINT fk_post FOREIGN KEY (post_id) REFERENCES post (id) ON DELETE CASCADE ON UPDATE RESTRICT,
    CONSTRAINT fk_category FOREIGN KEY (category_id) REFERENCES category (id) ON DELETE CASCADE ON UPDATE RESTRICT
);
INSERT INTO `post_category` (`post_id`, `category_id`) VALUES ('1', '1');
INSERT INTO `post_category` (`post_id`, `category_id`) VALUES ('1', '2');
SELECT * FROM post_category pc LEFT JOIN category c ON pc.category_id = c.id;

CREATE TABLE user (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    username VARCHAR (255) NOT NULL,
    password VARCHAR (255) NOT NULL,
    PRIMARY KEY (id)
);

