# Use node official image 
FROM node:18.15-alpine

# Set the working directory
WORKDIR /app

# Copy package json
COPY package*.json ./

# Install all dependencies
RUN npm install

# copiar los archivos y carpetas del proyecto al directorio de trabajo actual (es decir, la carpeta 'app')
COPY . .

# Command to run the node server
CMD ["npm", "run", "dev"]