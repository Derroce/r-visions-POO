<?php   
require_once 'Product.php'; // Assure-toi que la classe Product est incluse


class Clothing extends Product {
    private string $size;
    private string $color;
    private string $type;
    private int $material_fee;

    public function __construct(
        int $id = 0,
        string $name = '',
        array $photos = [],
        int $price = 0,
        string $description = '',
        int $quantity = 0,
        ?DateTime $createdAt = null,
        ?DateTime $updatedAt = null,
        int $category_id = 0,
        string $size = '',
        string $color = '',
        string $type = '',
        int $material_fee = 0
    ) {
        // Appelle le constructeur parent pour initialiser les propriétés héritées
        parent::__construct($id, $name, $photos, $price, $description, $quantity, $createdAt, $updatedAt, $category_id);
        
        // Initialisation des propriétés spécifiques à Clothing
        $this->size = $size;
        $this->color = $color;
        $this->type = $type;
        $this->material_fee = $material_fee;
    }

    // Getters et Setters pour les nouvelles propriétés
    public function getSize(): string {
        return $this->size;
    }

    public function setSize(string $size): void {
        $this->size = $size;
    }

    public function getColor(): string {
        return $this->color;
    }

    public function setColor(string $color): void {
        $this->color = $color;
    }

    public function getType(): string {
        return $this->type;
    }

    public function setType(string $type): void {
        $this->type = $type;
    }

    public function getMaterialFee(): int {
        return $this->material_fee;
    }

    public function setMaterialFee(int $material_fee): void {
        $this->material_fee = $material_fee;
    }
    public static function findOneById(int $id): ?Product {
        try {
            // Connexion à la base de données
            $pdo = new PDO('mysql:host=localhost;dbname=draft-shop', 'root', '');
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Requête SQL pour trouver un vêtement par ID
            $stmt = $pdo->prepare("
                SELECT p.*, c.size, c.color, c.type, c.material_fee 
                FROM product p
                JOIN clothing c ON p.id = c.product_id
                WHERE p.id = :id
            ");
            $stmt->bindParam(':id', $id);
            $stmt->execute();

            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            
            if ($result) {
                // Crée une nouvelle instance de Clothing avec les données récupérées
                $clothing = new Clothing(
                    $result['id'],
                    $result['name'],
                    json_decode($result['photos']),
                    $result['price'],
                    $result['description'],
                    $result['quantity'],
                    $result['created_at'] ? new DateTime($result['created_at']) : null,
                    $result['updated_at'] ? new DateTime($result['updated_at']) : null,
                    $result['category_id'],
                    $result['size'],
                    $result['color'],
                    $result['type'],
                    $result['material_fee']
                );

                return $clothing;
            } else {
                return null;
            }
        } catch (PDOException $e) {
            echo "Erreur lors de la récupération du produit : " . $e->getMessage();
            return null;
        } catch (Exception $e) {
            echo "Erreur générale : " . $e->getMessage();
            return null;
        }
    }

    public static function findAll(PDO $pdo): array {
        try {
            $pdo = new PDO('mysql:host=localhost;dbname=draft-shop', 'root', '');
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
            // Requête SQL pour récupérer tous les vêtements
            $stmt = $pdo->prepare("
                SELECT p.*, c.size, c.color, c.type, c.material_fee 
                FROM product p
                JOIN clothing c ON p.id = c.product_id
            ");
            $stmt->execute();
    
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
            $clothes = [];
            foreach ($results as $result) {
                $clothing = new Clothing(
                    $result['id'],
                    $result['name'],
                    json_decode($result['photos']),
                    $result['price'],
                    $result['description'],
                    $result['quantity'],
                    $result['created_at'] ? new DateTime($result['created_at']) : null,
                    $result['updated_at'] ? new DateTime($result['updated_at']) : null,
                    $result['category_id'],
                    $result['size'],
                    $result['color'],
                    $result['type'],
                    $result['material_fee']
                );
                $clothes[] = $clothing;
            }
    
            return $clothes;
        } catch (PDOException $e) {
            echo "Erreur lors de la récupération des vêtements : " . $e->getMessage();
            return [];
        } catch (Exception $e) {
            echo "Erreur générale : " . $e->getMessage();
            return [];
        }
    }
    
}
?>