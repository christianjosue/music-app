# Use node official image 
FROM node:18.15-alpine

# Set the working directory
WORKDIR /app

# Copy package json
COPY package*.json ./

# Install all dependencies
RUN npm install

# Copy project files and directories to the working directory 
COPY . .

# Command to run the node server
CMD ["npm", "run", "dev"]