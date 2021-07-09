package de.codefever.junit.storage;

/**
 * @author Eric Kubenka
 * creation date: 09.07.2021 - 08:20
 */
public interface StorageInterface {

    String store(Object obj);

    Object get(String uuid);
}
