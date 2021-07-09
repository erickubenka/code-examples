package de.codefever.junit.storage;

import java.util.HashMap;
import java.util.UUID;

/**
 * @author Eric Kubenka
 * creation date: 09.07.2021 - 08:22
 */
public class HashMapStorage implements StorageInterface {

    private final HashMap<String, Object> storage = new HashMap<>();

    @Override
    public String store(Object obj) {

        final UUID uuid = UUID.randomUUID();
        storage.put(uuid.toString(), obj);
        return uuid.toString();
    }

    @Override
    public Object get(final String uuid) {
        return storage.get(uuid);
    }
}
