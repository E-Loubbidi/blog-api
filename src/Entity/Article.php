<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\ArticleRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use ApiPlatform\Core\Annotation\ApiSubresource;

/**
 * @ApiResource(
 *     normalizationContext={"groups"={"article:read"}},
 *     denormalizationContext={"groups"={"article:write"}}
 * )
 * @ORM\Entity(repositoryClass=ArticleRepository::class)
 */
class Article
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     *
     * @Groups("article:read")
     */
    private $id;

    /**
    * @ORM\Column(type="string", length=255)
    *
    * @Groups({"article:read", "article:write"})
    */
    private $title;

    /**
    * @ORM\Column(type="string", length=255)
    *
    * @Groups("article:read")
    */
    private $slug;

    /**
    * @ORM\Column(type="text", nullable=true)
    *
    * @Groups({"article:read", "article:write"})
    */
    private $content;

    /**
    * @ORM\Column(type="string", length=255, nullable=true)
    *
    * @Groups({"article:read", "article:write"})
    */
    private $picture;

    /**
    * @ORM\Column(type="boolean")
    *
    * @Groups("article:read")
    */
    private $isPublished;

    /**
    * @ORM\Column(type="datetime", nullable=true)
    *
    * @Groups("article:read")
    */
    private $publishedAt;

    /**
    * @ORM\Column(type="datetime", nullable=true)
    *
    * @Groups("article:read")
    */
    private $updatedAt;

    /**
    * @ORM\Column(type="datetime")
    *
    * @Groups("article:read")
    */
    private $createdAt;

    /**
     * @ORM\OneToMany(targetEntity=Comment::class, mappedBy="article", orphanRemoval=true)
     *@ApiSubresource
     *
     * @Groups("article:read")
     */
     private $comments;

     /**
      * @ORM\ManyToMany(targetEntity=Tag::class, inversedBy="articles", cascade="persist")
      *
      * @Groups({"article:read", "article:write"})
      */
     private $tags;

    public function __construct(){
      $this->isPublished = false;
      $this->createdAt = new \Datetime();
      $this->comments = new ArrayCollection();
      $this->tags = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(){
      return $this->title;
    }

    public function setTitle($title){
      $this->title = $title;
    }

    public function getSlug(){
      return $this->slug;
    }

    public function setSlug($slug){
      $this->slug = $slug;
    }

    public function getContent(){
      return $this->content;
    }

    public function setContent($content){
      $this->content = $content;
    }

    public function getPicture(){
      return $this->picture;
    }

    public function setPicture($picture){
      $this->picture = $picture;
    }

    public function getIsPublished(){
      return $this->isPublished;
    }

    public function setIsPublished($isPublished){
      $this->isPublished = $isPublished;
    }

    public function getPublishedAt(){
      return $this->publishedAt;
    }

    public function setPublishedAt($publishedAt){
      $this->publishedAt = $publishedAt;
    }

    public function getUpdatedAt(){
      return $this->updatedAt;
    }

    public function setUpdatedAt($updatedAt){
      $this->updatedAt = $updatedAt;
    }

    public function getCreatedAt(): ?\DateTimeInterface{
      return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self{
      $this->createdAt = $createdAt;
      return $this;
    }

    /**
     * @return Collection|Comment[]
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): self{
      if(!$this->comments->contains($comment)){
        $this->comments[] = $comment;
        $comment->setArticle($this);
      }
      return $this;
    }

    public function removeComment(Comment $comment): self{
      if($this->comments->contains($comment)){
        $this->comments->removeElement($comment);
        if($comment->getArticle()===$this){
          $comment->setArticle(null);
        }
      }
      return $this;
    }

    /**
     * @return Collection|Tag[]
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }

    public function addTag(Tag $tag): self
    {
        if (!$this->tags->contains($tag)) {
            $this->tags[] = $tag;
        }

        return $this;
    }

    public function removeTag(Tag $tag): self
    {
        $this->tags->removeElement($tag);

        return $this;
    }
}
