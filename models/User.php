<?php

require_once __DIR__ . '/../config/MongoDB.php';

class User {
    private $collection;

    public function __construct() {
        $db = MongoDB::getInstance();
        $this->collection = $db->selectCollection('users');
        
        // Create unique index on email
        try {
            $this->collection->createIndex(['email' => 1], ['unique' => true]);
        } catch (Exception $e) {
            // Index might already exist
        }
    }

    public function create($data) {
        $user = [
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => password_hash($data['password'], PASSWORD_BCRYPT, ['cost' => 10]),
            'role' => $data['role'] ?? 'customer',
            'createdAt' => new MongoDB\BSON\UTCDateTime(),
            'updatedAt' => new MongoDB\BSON\UTCDateTime()
        ];

        try {
            $result = $this->collection->insertOne($user);
            $user['_id'] = $result->getInsertedId();
            unset($user['password']);
            return $user;
        } catch (MongoDB\Driver\Exception\BulkWriteException $e) {
            if (strpos($e->getMessage(), 'duplicate key') !== false) {
                throw new Exception("Email already exists");
            }
            throw $e;
        }
    }

    public function findByEmail($email) {
        return $this->collection->findOne(['email' => $email]);
    }

    public function findById($id) {
        try {
            $user = $this->collection->findOne(['_id' => new MongoDB\BSON\ObjectId($id)]);
            if ($user) {
                unset($user['password']);
            }
            return $user;
        } catch (Exception $e) {
            return null;
        }
    }

    public function verifyPassword($plainPassword, $hashedPassword) {
        return password_verify($plainPassword, $hashedPassword);
    }

    public function update($id, $data) {
        $updateData = [
            'updatedAt' => new MongoDB\BSON\UTCDateTime()
        ];

        if (isset($data['name'])) $updateData['name'] = $data['name'];
        if (isset($data['email'])) $updateData['email'] = $data['email'];
        if (isset($data['password'])) {
            $updateData['password'] = password_hash($data['password'], PASSWORD_BCRYPT, ['cost' => 10]);
        }

        try {
            $result = $this->collection->updateOne(
                ['_id' => new MongoDB\BSON\ObjectId($id)],
                ['$set' => $updateData]
            );
            return $result->getModifiedCount() > 0;
        } catch (Exception $e) {
            return false;
        }
    }

    public function delete($id) {
        try {
            $result = $this->collection->deleteOne(['_id' => new MongoDB\BSON\ObjectId($id)]);
            return $result->getDeletedCount() > 0;
        } catch (Exception $e) {
            return false;
        }
    }
}
