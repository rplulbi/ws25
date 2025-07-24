package main

import (
    "context"
    "log"
    "net/http"
    "os"
    "time"

    "github.com/gin-contrib/cors"
    "github.com/gin-gonic/gin"
    "github.com/joho/godotenv"
    "go.mongodb.org/mongo-driver/bson"
    "go.mongodb.org/mongo-driver/bson/primitive"
    "go.mongodb.org/mongo-driver/mongo"
    "go.mongodb.org/mongo-driver/mongo/options"
)

// Struct untuk data Buku
type Buku struct {
    ID         primitive.ObjectID `json:"id" bson:"_id,omitempty"`
    Judul      string             `json:"judul" bson:"judul"`
    Penulis    string             `json:"penulis" bson:"penulis"`
    TahunTerbit int                `json:"tahun_terbit" bson:"tahun_terbit"`
}

var collection *mongo.Collection

func main() {
    // 1. Muat environment variables dari file .env
    err := godotenv.Load()
    if err != nil {
        log.Fatal("Error loading .env file")
    }

    // 2. Koneksi ke MongoDB
    clientOptions := options.Client().ApplyURI(os.Getenv("MONGO_URI"))
    client, err := mongo.Connect(context.Background(), clientOptions)
    if err != nil {
        log.Fatal(err)
    }
    err = client.Ping(context.Background(), nil)
    if err != nil {
        log.Fatal(err)
    }
    log.Println("Connected to MongoDB!")
    collection = client.Database(os.Getenv("DATABASE_NAME")).Collection(os.Getenv("COLLECTION_NAME"))

    // 3. Setup Gin Router
    router := gin.Default()
	router.Static("/static", "./static")

    // Middleware CORS untuk mengizinkan request dari frontend
    router.Use(cors.New(cors.Config{
        AllowOrigins:     []string{"*"}, // Izinkan semua origin
        AllowMethods:     []string{"GET", "POST", "PUT", "DELETE", "OPTIONS"},
        AllowHeaders:     []string{"Origin", "Content-Type"},
        ExposeHeaders:    []string{"Content-Length"},
        AllowCredentials: true,
        MaxAge: 12 * time.Hour,
    }))

    // 4. Definisikan Routes (Endpoint API)
    router.POST("/buku", CreateBuku)       // Create
    router.GET("/buku", GetAllBuku)        // Read All
    router.GET("/buku/:id", GetBukuByID)   // Read One
    router.PUT("/buku/:id", UpdateBuku)    // Update
    router.DELETE("/buku/:id", DeleteBuku) // Delete

    // 5. Jalankan server
    router.Run(":8080")
}

// Handler Functions (CRUD Logic)

// CREATE
func CreateBuku(c *gin.Context) {
    var buku Buku
    if err := c.ShouldBindJSON(&buku); err != nil {
        c.JSON(http.StatusBadRequest, gin.H{"error": err.Error()})
        return
    }

    result, err := collection.InsertOne(context.Background(), buku)
    if err != nil {
        c.JSON(http.StatusInternalServerError, gin.H{"error": "Gagal menyimpan buku"})
        return
    }
    c.JSON(http.StatusCreated, result)
}

// READ ALL
func GetAllBuku(c *gin.Context) {
    var daftarBuku []Buku
    cursor, err := collection.Find(context.Background(), bson.M{})
    if err != nil {
        c.JSON(http.StatusInternalServerError, gin.H{"error": "Gagal mengambil data buku"})
        return
    }
    defer cursor.Close(context.Background())

    for cursor.Next(context.Background()) {
        var buku Buku
        cursor.Decode(&buku)
        daftarBuku = append(daftarBuku, buku)
    }
    c.JSON(http.StatusOK, daftarBuku)
}

// READ ONE
func GetBukuByID(c *gin.Context) {
    id, _ := primitive.ObjectIDFromHex(c.Param("id"))
    var buku Buku
    err := collection.FindOne(context.Background(), bson.M{"_id": id}).Decode(&buku)
    if err != nil {
        c.JSON(http.StatusNotFound, gin.H{"error": "Buku tidak ditemukan"})
        return
    }
    c.JSON(http.StatusOK, buku)
}

// UPDATE
func UpdateBuku(c *gin.Context) {
    id, _ := primitive.ObjectIDFromHex(c.Param("id"))
    var buku Buku
    if err := c.ShouldBindJSON(&buku); err != nil {
        c.JSON(http.StatusBadRequest, gin.H{"error": err.Error()})
        return
    }

    update := bson.M{
        "$set": bson.M{
            "judul":       buku.Judul,
            "penulis":     buku.Penulis,
            "tahun_terbit": buku.TahunTerbit,
        },
    }
    _, err := collection.UpdateOne(context.Background(), bson.M{"_id": id}, update)
    if err != nil {
        c.JSON(http.StatusInternalServerError, gin.H{"error": "Gagal memperbarui buku"})
        return
    }
    c.JSON(http.StatusOK, gin.H{"message": "Buku berhasil diperbarui"})
}

// DELETE
func DeleteBuku(c *gin.Context) {
    id, _ := primitive.ObjectIDFromHex(c.Param("id"))
    _, err := collection.DeleteOne(context.Background(), bson.M{"_id": id})
    if err != nil {
        c.JSON(http.StatusInternalServerError, gin.H{"error": "Gagal menghapus buku"})
        return
    }
    c.JSON(http.StatusOK, gin.H{"message": "Buku berhasil dihapus"})
}