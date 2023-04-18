FROM node:18
LABEL author="Eric Kubenka<code@code-fever.de>"

# replace this with your application's default port
EXPOSE 8888

# replace with your credentials
RUN git config --global user.email "code@example.com" && \
git config --global user.name "example"

RUN npm install -g @angular/cli
