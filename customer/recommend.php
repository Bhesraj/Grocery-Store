<?php
include("../config/db.php");

/**
 * Item-based Content-Based Filtering
 * Recommends similar products based on feature similarity
 */

function getRecommendations($product_id, $conn, $limit = 5) {

    // Fetch current product
    $stmt = $conn->prepare("SELECT features FROM products WHERE id = ?");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $target = $result->fetch_assoc();

    if (!$target) return [];

    $target_features = $target['features'];

    // Fetch all other products
    $stmt_all = $conn->prepare("SELECT * FROM products WHERE id != ?");
    $stmt_all->bind_param("i", $product_id);
    $stmt_all->execute();
    $result_all = $stmt_all->get_result();

    $similarities = [];

    while ($row = $result_all->fetch_assoc()) {
        similar_text($target_features, $row['features'], $percent);

        $similarities[] = [
            'product' => $row,
            'similarity' => $percent
        ];
    }

    // Sort by similarity (descending)
    usort($similarities, function ($a, $b) {
        return $b['similarity'] <=> $a['similarity'];
    });

    return array_slice($similarities, 0, $limit);
}
