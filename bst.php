<?php
class TreeNode {
    public string $value;
    public ?TreeNode $left = null;
    public ?TreeNode $right = null;

    public function __construct(string $value) {
        $this->value = $value;
    }
}

class IterativeBST {
    private ?TreeNode $root = null;

    public function add(string $value): void {
        $newNode = new TreeNode($value);

        if ($this->root === null) {
            $this->root = $newNode;
            return;
        }

        $current = $this->root;
        while (true) {
            if (strcasecmp($value, $current->value) < 0) {
                if ($current->left === null) {
                    $current->left = $newNode;
                    break;
                }
                $current = $current->left;
            } else {
                if ($current->right === null) {
                    $current->right = $newNode;
                    break;
                }
                $current = $current->right;
            }
        }
    }

    public function inorder(): array {
        $result = [];
        $stack = [];
        $current = $this->root;

        while ($current !== null || !empty($stack)) {
            while ($current !== null) {
                array_push($stack, $current);
                $current = $current->left;
            }

            $current = array_pop($stack);
            $result[] = $current->value;
            $current = $current->right;
        }
        return $result;
    }

    public function find(string $value): bool {
        $current = $this->root;
        while ($current !== null) {
            $cmp = strcasecmp($value, $current->value);
            if ($cmp === 0) return true;
            $current = $cmp < 0 ? $current->left : $current->right;
        }
        return false;
    }

    public function getMin(): ?string {
        $current = $this->root;
        if (!$current) return null;
        while ($current->left !== null) {
            $current = $current->left;
        }
        return $current->value;
    }

    public function getMax(): ?string {
        $current = $this->root;
        if (!$current) return null;
        while ($current->right !== null) {
            $current = $current->right;
        }
        return $current->value;
    }
}

if (php_sapi_name() === 'cli') {
    $tree = new IterativeBST();
    $books = ["Moby Dick", "The Hobbit", "Pride and Prejudice", "Dracula", "1984"];

    foreach ($books as $book) {
        $tree->add($book);
    }

    echo "📚 Inorder Traversal:\n";
    print_r($tree->inorder());

    echo "\n🔍 Searching for 'Dracula': ";
    echo $tree->find('Dracula') ? "✅ Found\n" : "❌ Not Found\n";

    echo "\n🌿 Minimum Value: " . $tree->getMin() . "\n";
    echo "🌳 Maximum Value: " . $tree->getMax() . "\n";
}
?>
