<?php

namespace App\Constants;

class ApiStatus
{
    const CONST_TRUE = true;
    const CONST_FALSE = false;
    const HTTP_200 = 200;  // ✅ OK → Request was successful
    const HTTP_201 = 201;  // ✅ Created → Request succeeded and a new resource was created
    const HTTP_400 = 400;  // ❌ The server cannot process the request because the client sent invalid or incomplete data
    const HTTP_404 = 404;  // ❌ Not Found → Resource does not exist
    const HTTP_422 = 422;  // ⚠️ Unprocessable Entity → Validation failed or bad input
    const HTTP_401 = 401;  // 🔒 Unauthorized → User not authenticated / invalid token
    const HTTP_409 = 409;  // 🔒 Unauthorized → User not authenticated / invalid token
    const HTTP_500 = 500;  // 💥 Internal Server Error → Unexpected server-side error

    // Add other constants as needed
}
