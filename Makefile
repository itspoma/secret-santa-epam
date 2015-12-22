IMAGE_NAME = epam/image
CONTAINER_NAME = secret-santa

clean:
	docker rmi -f ${IMAGE_NAME} 2>/dev/null || true
	docker images | grep ${IMAGE_NAME} || true
	docker rm -f $$(docker ps -a -q) || true
	docker ps -a

rebuild:
	docker build -t ${IMAGE_NAME} .

build:
	docker build --rm -t ${IMAGE_NAME} .

stop:
	docker rm -f ${CONTAINER_NAME} 2>/dev/null || true

run: stop
	docker run --name=${CONTAINER_NAME} -p 80:8080 -v $$PWD/shared:/shared -ti -d ${IMAGE_NAME}

ssh:
	docker exec -ti ${CONTAINER_NAME} bash

.PHONY: clean build run