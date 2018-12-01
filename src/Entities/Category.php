<?php
namespace Project\Entities;
/**
 * Users
 *
 * @Entity @Table(name="category")
 * @Entity(repositoryClass="Project\Entities\Repository\CategoryRepository")
 */
class Category
{
    /**
     * @Id @Column(type="integer")
     * @GeneratedValue
     */
    private $id;

    /**
     * @Column(name="name", type="string", length=100, nullable=false)
     */
    private $name = '';
}
