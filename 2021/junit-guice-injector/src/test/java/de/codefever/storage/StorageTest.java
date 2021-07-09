package de.codefever.storage;

import com.google.inject.Inject;
import de.codefever.junit.inject.JunitModule;
import de.codefever.junit.inject.WithGuice;
import de.codefever.junit.storage.StorageInterface;
import org.junit.jupiter.api.Assertions;
import org.junit.jupiter.api.Test;

@WithGuice(modules = JunitModule.class)
public class StorageTest {

    @Inject
    StorageInterface storage;

    @Test
    public void testT01_SimplePassedTestCase() {

        Assertions.assertNotNull(storage, "Guice modules injected successfully");

        final String stringIdent = storage.store("Simple String");
        final String integerIdent = storage.store(10);
        Assertions.assertEquals(storage.get(stringIdent), "Simple String");
        Assertions.assertEquals(storage.get(integerIdent), 10);
    }
}
