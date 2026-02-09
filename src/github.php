<?php

function fetch_languages(string $user, string $token): array
{
    $query = <<<GQL
query (\$login: String!) {
  user(login: \$login) {
    repositories(first: 100, isFork: false, ownerAffiliations: OWNER) {
      nodes {
        languages(first: 10) {
          edges {
            size
            node { name }
          }
        }
      }
    }
  }
}
GQL;

    $payload = json_encode([
        'query' => $query,
        'variables' => ['login' => $user],
    ]);

    $ch = curl_init('https://api.github.com/graphql');
    curl_setopt_array($ch, [
        CURLOPT_POST           => true,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER     => [
            "Authorization: Bearer $token",
            "User-Agent: github-language-stats",
            "Content-Type: application/json",
        ],
        CURLOPT_POSTFIELDS     => $payload,
    ]);

    $resp = curl_exec($ch);

    if ($resp === false) {
        throw new RuntimeException('GitHub API request failed');
    }

    curl_close($ch);

    return json_decode($resp, true);
}
